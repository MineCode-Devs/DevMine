<?php



namespace devmine\inventory\solidentity;

use devmine\inventory\layout\ChestInventory;
use devmine\inventory\layout\DoubleChestInventory;
use devmine\inventory\layout\InventoryHolder;
use devmine\inventory\items\Item;
use devmine\levels\format\FullChunk;
use devmine\server\calculations\Vector3;
use devmine\creatures\player\NBT;

use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\ListTag;
use devmine\creatures\player\tag\IntTag;

use devmine\creatures\player\tag\StringTag;

class Chest extends Spawnable implements InventoryHolder, Container, Nameable{

	/** @var ChestInventory */
	protected $inventory;
	/** @var DoubleChestInventory */
	protected $doubleInventory = null;

	public function __construct(FullChunk $chunk, CompoundTag $nbt){
		parent::__construct($chunk, $nbt);
		$this->inventory = new ChestInventory($this);

		if(!isset($this->namedtag->Items) or !($this->namedtag->Items instanceof ListTag)){
			$this->namedtag->Items = new ListTag("Items", []);
			$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		}

		for($i = 0; $i < $this->getSize(); ++$i){
			$this->inventory->setItem($i, $this->getItem($i));
		}
	}

	public function close(){
		if($this->closed === false){
			foreach($this->getInventory()->getViewers() as $player){
				$player->removeWindow($this->getInventory());
			}

			foreach($this->getInventory()->getViewers() as $player){
				$player->removeWindow($this->getRealInventory());
			}
			parent::close();
		}
	}

	public function saveNBT(){
		$this->namedtag->Items = new ListTag("Items", []);
		$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		for($index = 0; $index < $this->getSize(); ++$index){
			$this->setItem($index, $this->inventory->getItem($index));
		}
	}

	/**
	 * @return int
	 */
	public function getSize(){
		return 27;
	}

	/**
	 * @param $index
	 *
	 * @return int
	 */
	protected function getSlotIndex($index){
		foreach($this->namedtag->Items as $i => $slot){
			if((int) $slot["Slot"] === (int) $index){
				return (int) $i;
			}
		}

		return -1;
	}

	/**
	 * This method should not be used by plugins, use the Inventory
	 *
	 * @param int $index
	 *
	 * @return Item
	 */
	public function getItem($index){
		$i = $this->getSlotIndex($index);
		if($i < 0){
			return Item::get(Item::AIR, 0, 0);
		}else{
			return NBT::getItemHelper($this->namedtag->Items[$i]);
		}
	}

	/**
	 * This method should not be used by plugins, use the Inventory
	 *
	 * @param int  $index
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function setItem($index, Item $item){
		$i = $this->getSlotIndex($index);

		$d = NBT::putItemHelper($item, $index);

		if($item->getId() === Item::AIR or $item->getCount() <= 0){
			if($i >= 0){
				unset($this->namedtag->Items[$i]);
			}
		}elseif($i < 0){
			for($i = 0; $i <= $this->getSize(); ++$i){
				if(!isset($this->namedtag->Items[$i])){
					break;
				}
			}
			$this->namedtag->Items[$i] = $d;
		}else{
			$this->namedtag->Items[$i] = $d;
		}

		return true;
	}

	/**
	 * @return ChestInventory|DoubleChestInventory
	 */
	public function getInventory(){
		if($this->isPaired() and $this->doubleInventory === null){
			$this->checkPairing();
		}
		return $this->doubleInventory instanceof DoubleChestInventory ? $this->doubleInventory : $this->inventory;
	}

	/**
	 * @return ChestInventory
	 */
	public function getRealInventory(){
		return $this->inventory;
	}

	protected function checkPairing(){
		if(($pair = $this->getPair()) instanceof Chest){
			if(!$pair->isPaired()){
				$pair->createPair($this);
				$pair->checkPairing();
			}
			if($this->doubleInventory === null){
				if(($pair->x + ($pair->z << 15)) > ($this->x + ($this->z << 15))){ //Order them correctly
					$this->doubleInventory = new DoubleChestInventory($pair, $this);
				}else{
					$this->doubleInventory = new DoubleChestInventory($this, $pair);
				}
			}
		}else{
			$this->doubleInventory = null;
			unset($this->namedtag->pairx, $this->namedtag->pairz);
		}
	}

	public function getName() : string{
		return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Chest";
	}

	public function hasName(){
		return isset($this->namedtag->CustomName);
	}

	public function setName($str){
		if($str === ""){
			unset($this->namedtag->CustomName);
			return;
		}

		$this->namedtag->CustomName = new StringTag("CustomName", $str);
	}

	public function isPaired(){
		if(!isset($this->namedtag->pairx) or !isset($this->namedtag->pairz)){
			return false;
		}

		return true;
	}

	/**
	 * @return Chest
	 */
	public function getPair(){
		if($this->isPaired()){
			$solidentity = $this->getLevel()->getsolidentity(new Vector3((int) $this->namedtag["pairx"], $this->y, (int) $this->namedtag["pairz"]));
			if($solidentity instanceof Chest){
				return $solidentity;
			}
		}

		return null;
	}

	public function pairWith(Chest $solidentity){
		if($this->isPaired() or $solidentity->isPaired()){
			return false;
		}

		$this->createPair($solidentity);

		$this->spawnToAll();
		$solidentity->spawnToAll();
		$this->checkPairing();

		return true;
	}

	private function createPair(Chest $solidentity){
		$this->namedtag->pairx = new IntTag("pairx", $solidentity->x);
		$this->namedtag->pairz = new IntTag("pairz", $solidentity->z);

		$solidentity->namedtag->pairx = new IntTag("pairx", $this->x);
		$solidentity->namedtag->pairz = new IntTag("pairz", $this->z);
	}

	public function unpair(){
		if(!$this->isPaired()){
			return false;
		}

		$solidentity = $this->getPair();
		unset($this->namedtag->pairx, $this->namedtag->pairz);

		$this->spawnToAll();

		if($solidentity instanceof Chest){
			unset($solidentity->namedtag->pairx, $solidentity->namedtag->pairz);
			$solidentity->checkPairing();
			$solidentity->spawnToAll();
		}
		$this->checkPairing();

		return true;
	}

	public function getSpawnCompound(){
		if($this->isPaired()){
			$c = new CompoundTag("", [
				new StringTag("id", solidentity::CHEST),
				new IntTag("x", (int) $this->x),
				new IntTag("y", (int) $this->y),
				new IntTag("z", (int) $this->z),
				new IntTag("pairx", (int) $this->namedtag["pairx"]),
				new IntTag("pairz", (int) $this->namedtag["pairz"])
			]);
		}else{
			$c = new CompoundTag("", [
				new StringTag("id", solidentity::CHEST),
				new IntTag("x", (int) $this->x),
				new IntTag("y", (int) $this->y),
				new IntTag("z", (int) $this->z)
			]);
		}

		if($this->hasName()){
			$c->CustomName = $this->namedtag->CustomName;
		}

		return $c;
	}
}
