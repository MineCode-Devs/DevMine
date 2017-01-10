<?php



namespace devmine\inventory\solidentity;

use devmine\inventory\layout\EnchantInventory;
use devmine\inventory\layout\InventoryHolder;
use devmine\inventory\items\Item;
use devmine\worlds\format\Chunk;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\StringTag;

class EnchantTable extends Spawnable implements Nameable{

	public function __construct(Chunk $chunk, CompoundTag $nbt){
		parent::__construct($chunk, $nbt);
	}

	public function getName() : string{
		return $this->hasName() ? $this->namedtag->CustomName->getValue() : "Enchanting Table";
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

	public function getSpawnCompound(){
		$nbt = new CompoundTag("", [
			new StringTag("id", Tile::ENCHANT_TABLE),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z)
		]);

		if($this->hasName()){
			$nbt->CustomName = $this->namedtag->CustomName;
		}

		return $nbt;
	}
}
