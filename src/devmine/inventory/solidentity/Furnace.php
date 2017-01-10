<?php



namespace devmine\inventory\solidentity;

use devmine\inventory\blocks\Block;
use devmine\events\inventory\FurnaceBurnEvent;
use devmine\events\inventory\FurnaceSmeltEvent;
use devmine\inventory\layout\FurnaceInventory;
use devmine\inventory\layout\FurnaceRecipe;
use devmine\inventory\layout\InventoryHolder;
use devmine\inventory\items\Item;
use devmine\worlds\format\Chunk;
use devmine\creatures\player\NBT;

use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\ListTag;
use devmine\creatures\player\tag\ShortTag;
use devmine\creatures\player\tag\StringTag;
use devmine\creatures\player\tag\IntTag;
use devmine\server\network\Network;
use devmine\server\network\protocol\ContainerSetDataPacket;

class Furnace extends Spawnable implements InventoryHolder, Container, Nameable{
	/** @var FurnaceInventory */
	protected $inventory;

	public function __construct(Chunk $chunk, CompoundTag $nbt){
		parent::__construct($chunk, $nbt);
		$this->inventory = new FurnaceInventory($this);

		if(!isset($this->namedtag->Items) or !($this->namedtag->Items instanceof ListTag)){
			$this->namedtag->Items = new ListTag("Items", []);
			$this->namedtag->Items->setTagType(NBT::TAG_Compound);
		}

		for($i = 0; $i < $this->getSize(); ++$i){
			$this->inventory->setItem($i, $this->getItem($i));
		}

		if(!isset($this->namedtag->BurnTime) or $this->namedtag["BurnTime"] < 0){
			$this->namedtag->BurnTime = new ShortTag("BurnTime", 0);
		}
		if(!isset($this->namedtag->CookTime) or $this->namedtag["CookTime"] < 0 or ($this->namedtag["BurnTime"] === 0 and $this->namedtag["CookTime"] > 0)){
			$this->namedtag->CookTime = new ShortTag("CookTime", 0);
		}
		if(!isset($this->namedtag->MaxTime)){
			$this->namedtag->MaxTime = new ShortTag("BurnTime", $this->namedtag["BurnTime"]);
			$this->namedtag->BurnTicks = new ShortTag("BurnTicks", 0);
		}
		if($this->namedtag["BurnTime"] > 0){
			$this->scheduleUpdate();
		}
	}

	public function getName() : string{
		return isset($this->namedtag->CustomName) ? $this->namedtag->CustomName->getValue() : "Furnace";
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

	public function close(){
		if($this->closed === false){
			foreach($this->getInventory()->getViewers() as $player){
				$player->removeWindow($this->getInventory());
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
		return 3;
	}

	/**
	 * @param $index
	 *
	 * @return int
	 */
	protected function getSlotIndex($index){
		foreach($this->namedtag->Items as $i => $slot){
			if($slot["Slot"] === $index){
				return $i;
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
			return Item::nbtDeserialize($this->namedtag->Items[$i]);
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
			$this->namedtag->Items[$i] = $item->nbtSerialize($index);
		}else{
			$this->namedtag->Items[$i] = $item->nbtSerialize($index);
		}

		return true;
	}

	/**
	 * @return FurnaceInventory
	 */
	public function getInventory(){
		return $this->inventory;
	}

	protected function checkFuel(Item $fuel){
		$this->server->getPluginManager()->callEvent($ev = new FurnaceBurnEvent($this, $fuel, $fuel->getFuelTime()));

		if($ev->isCancelled()){
			return;
		}

		$this->namedtag->MaxTime = new ShortTag("MaxTime", $ev->getBurnTime());
		$this->namedtag->BurnTime = new ShortTag("BurnTime", $ev->getBurnTime());
		$this->namedtag->BurnTicks = new ShortTag("BurnTicks", 0);
		if($this->getBlock()->getId() === Item::FURNACE){
			$this->getLevel()->setBlock($this, Block::get(Item::BURNING_FURNACE, $this->getBlock()->getDamage()), true);
		}

		if($this->namedtag["BurnTime"] > 0 and $ev->isBurning()){
			$fuel->setCount($fuel->getCount() - 1);
			if($fuel->getCount() === 0){
				$fuel = Item::get(Item::AIR, 0, 0);
			}
			$this->inventory->setFuel($fuel);
		}
	}

	public function onUpdate(){
		if($this->closed === true){
			return false;
		}

		$this->timings->startTiming();

		$ret = false;

		$fuel = $this->inventory->getFuel();
		$raw = $this->inventory->getSmelting();
		$product = $this->inventory->getResult();
		$smelt = $this->server->getCraftingManager()->matchFurnaceRecipe($raw);
		$canSmelt = ($smelt instanceof FurnaceRecipe and $raw->getCount() > 0 and (($smelt->getResult()->equals($product) and $product->getCount() < $product->getMaxStackSize()) or $product->getId() === Item::AIR));

		if($this->namedtag["BurnTime"] <= 0 and $canSmelt and $fuel->getFuelTime() !== null and $fuel->getCount() > 0){
			$this->checkFuel($fuel);
		}

		if($this->namedtag["BurnTime"] > 0){
			$this->namedtag->BurnTime = new ShortTag("BurnTime", $this->namedtag["BurnTime"] - 1);
			$this->namedtag->BurnTicks = new ShortTag("BurnTicks", ceil(($this->namedtag["BurnTime"] / $this->namedtag["MaxTime"] * 200)));

			if($smelt instanceof FurnaceRecipe and $canSmelt){
				$this->namedtag->CookTime = new ShortTag("CookTime", $this->namedtag["CookTime"] + 1);
				if($this->namedtag["CookTime"] >= 200){ //10 seconds
					$product = Item::get($smelt->getResult()->getId(), $smelt->getResult()->getDamage(), $product->getCount() + 1);

					$this->server->getPluginManager()->callEvent($ev = new FurnaceSmeltEvent($this, $raw, $product));

					if(!$ev->isCancelled()){
						$this->inventory->setResult($ev->getResult());
						$raw->setCount($raw->getCount() - 1);
						if($raw->getCount() === 0){
							$raw = Item::get(Item::AIR, 0, 0);
						}
						$this->inventory->setSmelting($raw);
					}

					$this->namedtag->CookTime = new ShortTag("CookTime", $this->namedtag["CookTime"] - 200);
				}
			}elseif($this->namedtag["BurnTime"] <= 0){
				$this->namedtag->BurnTime = new ShortTag("BurnTime", 0);
				$this->namedtag->CookTime = new ShortTag("CookTime", 0);
				$this->namedtag->BurnTicks = new ShortTag("BurnTicks", 0);
			}else{
				$this->namedtag->CookTime = new ShortTag("CookTime", 0);
			}
			$ret = true;
		}else{
			if($this->getBlock()->getId() === Item::BURNING_FURNACE){
				$this->getLevel()->setBlock($this, Block::get(Item::FURNACE, $this->getBlock()->getDamage()), true);
			}
			$this->namedtag->BurnTime = new ShortTag("BurnTime", 0);
			$this->namedtag->CookTime = new ShortTag("CookTime", 0);
			$this->namedtag->BurnTicks = new ShortTag("BurnTicks", 0);
		}

		foreach($this->getInventory()->getViewers() as $player){
			$windowId = $player->getWindowId($this->getInventory());
			if($windowId > 0){
				$pk = new ContainerSetDataPacket();
				$pk->windowid = $windowId;
				$pk->property = 0; //Smelting
				$pk->value = floor($this->namedtag["CookTime"]);
				$player->dataPacket($pk);

				$pk = new ContainerSetDataPacket();
				$pk->windowid = $windowId;
				$pk->property = 1; //Fire icon
				$pk->value = $this->namedtag["BurnTicks"];
				$player->dataPacket($pk);
			}

		}

		$this->lastUpdate = microtime(true);

		$this->timings->stopTiming();

		return $ret;
	}
	
	public function getSpawnCompound(){
		$nbt = new CompoundTag("", [
			new StringTag("id", Tile::FURNACE),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z),
			new ShortTag("BurnTime", $this->namedtag["BurnTime"]),
			new ShortTag("CookTime", $this->namedtag["CookTime"]),
			//new ShortTag("BurnDuration", $this->namedtag["BurnTicks"])
		]);
		
		if($this->hasName()){
			$nbt->CustomName = $this->namedtag->CustomName;
		}
		return $nbt;
	}
}
