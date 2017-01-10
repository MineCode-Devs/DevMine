<?php



namespace devmine\inventory\layout;

use devmine\inventory\items\Item;
use devmine\worlds\Position;
use devmine\creatures\player;

class AnvilInventory extends TemporaryInventory{

	const TARGET = 0;
	const SACRIFICE = 1;
	const RESULT = 2;


	public function __construct(Position $pos){
		parent::__construct(new FakeBlockMenu($this, $pos), InventoryType::get(InventoryType::ANVIL));
	}

	/**
	 * @return FakeBlockMenu
	 */
	public function getHolder(){
		return $this->holder;
	}

	public function getResultSlotIndex(){
		return self::RESULT;
	}

	public function onRename(Player $player, Item $resultItem) : bool{
		if(!$resultItem->deepEquals($this->getItem(self::TARGET), true, false, true)){
			//Item does not match target item. Everything must match except the tags.
			return false;
		}

		if($player->getXpLevel() < $resultItem->getRepairCost()){ //Not enough exp
			return false;
		}
		$player->takeXpLevel($resultItem->getRepairCost());
		
		$this->clearAll();
		if(!$player->getServer()->allowInventoryCheats and !$player->isCreative()){
			if(!$player->getFloatingInventory()->canAddItem($resultItem)){
				return false;
			}
			$player->getFloatingInventory()->addItem($resultItem);
		}
		return true;
	}

	public function processSlotChange(Transaction $transaction): bool{
		if($transaction->getSlot() === $this->getResultSlotIndex()){
			return false;
		}
		return true;
	}

	public function onSlotChange($index, $before, $send){
		//Do not send anvil slot updates to anyone. This will cause a client crash.
	}

	public function onClose(Player $who){
		parent::onClose($who);

		$this->getHolder()->getLevel()->dropItem($this->getHolder()->add(0.5, 0.5, 0.5), $this->getItem(0));
		$this->getHolder()->getLevel()->dropItem($this->getHolder()->add(0.5, 0.5, 0.5), $this->getItem(1));
		$this->clear(0);
		$this->clear(1);
		$this->clear(2);
	}

}