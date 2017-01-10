<?php



namespace devmine\inventory\solidentity;

use devmine\inventory\items\Item;
use devmine\worlds\format\Chunk;
use devmine\creatures\player\tag\ByteTag;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\FloatTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\StringTag;

class ItemFrame extends Spawnable{
	public function __construct(Chunk $chunk, CompoundTag $nbt){
		if(!isset($nbt->ItemRotation)){
			$nbt->ItemRotation = new ByteTag("ItemRotation", 0);
		}
		if(!isset($nbt->ItemDropChance)){
			$nbt->ItemDropChance = new FloatTag("ItemDropChance", 1.0);
		}
		parent::__construct($chunk, $nbt);
	}
	
	public function dropItem(){
		if(lcg_value() < $this->getDropChance() and $this->hasItem()){
			$this->level->dropItem($this, $this->getItem());
		}
		$this->setItem(null);
	}
	
	public function hasItem() : bool{
		return $this->getItem()->getId() !== Item::AIR;
	}
	
	public function getItem() : Item{
		if(isset($this->namedtag->Item)){
			return Item::nbtDeserialize($this->namedtag->Item);
		}else{
			return Item::get(Item::AIR);
		}
	}
	
	public function setItem(Item $item = null){
		if($item !== null and $item->getId() !== Item::AIR){
			$this->namedtag->Item = $item->nbtSerialize(-1, "Item");
		}else{
			unset($this->namedtag->Item);
		}
		$this->onChanged();
	}
	
	public function getItemRotation() : int{
		return $this->namedtag->ItemRotation->getValue();
	}
	
	public function setItemRotation(int $rotation){
		$this->namedtag->ItemRotation = new ByteTag("ItemRotation", $rotation);
		$this->onChanged();
	}
	
	public function getItemDropChance() : float{
		return $this->namedtag->ItemDropChance->getValue();
	}
	
	public function setItemDropChance(float $chance){
		$this->namedtag->ItemDropChance = new FloatTag("ItemDropChance", $chance);
		$this->onChanged();
	}
	
	public function getSpawnCompound(){
		$tag = new CompoundTag("", [
			new StringTag("id", Tile::ITEM_FRAME),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z),
			$this->namedtag->ItemDropChance,
			$this->namedtag->ItemRotation,
		]);
		if($this->hasItem()){
			$tag->Item = $this->namedtag->Item;
		}
		return $tag;
	}
}
