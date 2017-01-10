<?php

namespace devmine\inventory\solidentity;
use devmine\worlds\format\Chunk;
use devmine\creatures\player\tag\ByteTag;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\StringTag;
class Skull extends Spawnable{
	const TYPE_SKELETON = 0;
	const TYPE_WITHER = 1;
	const TYPE_ZOMBIE = 2;
	const TYPE_HUMAN = 3;
	const TYPE_CREEPER = 4;
	public function __construct(Chunk $chunk, CompoundTag $nbt){
		if(!isset($nbt->SkullType)){
			$nbt->SkullType = new ByteTag("SkullType", 0);
		}
		if(!isset($nbt->Rot)){
			$nbt->Rot = new ByteTag("Rot", 0);
		}
		parent::__construct($chunk, $nbt);
	}
	public function setType($type){
		if($type >= 0 && $type <= 4){
			$this->namedtag->SkullType = new ByteTag("SkullType", $type);
			$this->onChanged();
			return true;
		}
		return false;
	}
	public function getType(){
		return $this->namedtag["SkullType"];
	}
	public function getSpawnCompound(){
		return new CompoundTag("", [
			new StringTag("id", Tile::SKULL),
			$this->namedtag->SkullType,
			$this->namedtag->Rot,
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z)
		]);
	}
}
