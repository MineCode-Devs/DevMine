<?php



namespace devmine\inventory\layout;

use devmine\server\events\inventory\InventoryTransactionEvent;
use devmine\inventory\items\Item;
use devmine\Player;
use devmine\Server;

/**
 * This TransactionGroup only allows doing Transaction between one / two inventories
 */
class SimpleTransactionGroup implements TransactionGroup{
	private $creationTime;
	protected $hasExecuted = false;
	/** @var Player */
	protected $source = null;

	/** @var Inventory[] */
	protected $inventories = [];

	/** @var Transaction[] */
	protected $transactions = [];

	/**
	 * @param Player $source
	 */
	public function __construct(Player $source = null){
		$this->creationTime = microtime(true);
		$this->source = $source;
	}

	/**
	 * @return Player
	 */
	public function getSource(){
		return $this->source;
	}

	public function getCreationTime(){
		return $this->creationTime;
	}

	public function getInventories(){
		return $this->inventories;
	}

	public function getTransactions(){
		return $this->transactions;
	}

	public function addTransaction(Transaction $transaction){
		if(isset($this->transactions[spl_object_hash($transaction)])){
			return;
		}
		foreach($this->transactions as $hash => $tx){
			if($tx->getInventory() === $transaction->getInventory() and $tx->getSlot() === $transaction->getSlot()){
				if($transaction->getCreationTime() >= $tx->getCreationTime()){
					unset($this->transactions[$hash]);
				}else{
					return;
				}
			}
		}
		$this->transactions[spl_object_hash($transaction)] = $transaction;
		$this->inventories[spl_object_hash($transaction->getInventory())] = $transaction->getInventory();
	}

	/**
	 * @param Item[] $needItems
	 * @param Item[] $haveItems
	 *
	 * @return bool
	 */
	protected function matchItems(array &$needItems, array &$haveItems){
		foreach($this->transactions as $key => $ts){
			if($ts->getTargetItem()->getId() !== Item::AIR){
				$needItems[] = $ts->getTargetItem();
			}
			$checkSourceItem = $ts->getInventory()->getItem($ts->getSlot());
			$sourceItem = $ts->getSourceItem();
			if(!$checkSourceItem->deepEquals($sourceItem, true, false) or $sourceItem->getCount() !== $checkSourceItem->getCount()){
				return false;
			}
			if($sourceItem->getId() !== Item::AIR){
				$haveItems[] = $sourceItem;
			}
		}

		foreach($needItems as $i => $needItem){
			foreach($haveItems as $j => $haveItem){
				if($needItem->deepEquals($haveItem, true, false)){
					$amount = min($needItem->getCount(), $haveItem->getCount());
					$needItem->setCount($needItem->getCount() - $amount);
					$haveItem->setCount($haveItem->getCount() - $amount);
					if($haveItem->getCount() === 0){
						unset($haveItems[$j]);
					}
					if($needItem->getCount() === 0){
						unset($needItems[$i]);
						break;
					}
				}
			}
		}

		return true;
	}

	public function canExecute(){
		$haveItems = [];
		$needItems = [];

		return $this->matchItems($haveItems, $needItems) and count($haveItems) === 0 and count($needItems) === 0 and count($this->transactions) > 0;
	}

	public function execute(){
		if($this->hasExecuted() or !$this->canExecute()){
			return false;
		}

		Server::getInstance()->getPluginManager()->callEvent($ev = new InventoryTransactionEvent($this));
		if($ev->isCancelled()){
			foreach($this->inventories as $inventory){
				if($inventory instanceof PlayerInventory){
					$inventory->sendArmorContents($this->getSource());
				}
				$inventory->sendContents($this->getSource());
			}

			return false;
		}

		foreach($this->transactions as $transaction){
			$transaction->getInventory()->setItem($transaction->getSlot(), $transaction->getTargetItem());
		}

		$this->hasExecuted = true;

		return true;
	}

	public function hasExecuted(){
		return $this->hasExecuted;
	}
}