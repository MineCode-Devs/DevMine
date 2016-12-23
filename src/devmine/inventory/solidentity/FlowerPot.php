<?php



/*
 * Originally by @beito123
 * https://github.com/beito123/devmine-MP-Plugins/blob/master/test%2FFlowerPot%2Fsrc%2Fbeito%2FFlowerPot%2Fomake%2FSkull.php
 */

namespace devmine\inventory\solidentity;

use devmine\inventory\blocks\Block;
use devmine\levels\format\FullChunk;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\ShortTag;
use devmine\creatures\player\tag\StringTag;

class FlowerPot extends Spawnable{

	public function __construct(FullChunk $chunk, CompoundTag $nbt){
        parent::__construct($chunk, $nbt);
		if(!isset($nbt->item)){
			$nbt->item = new ShortTag("item", 0);
		}
		if(!isset($nbt->mData)){
			$nbt->mData = new IntTag("mData", 0);
		}
	}

	public function getFlowerPotItem(){
		return $this->namedtag["item"];
	}

	public function getFlowerPotData(){
		return $this->namedtag["mData"];
	}

	public function setFlowerPotData($item, $data){
		$this->namedtag->item = new ShortTag("item", (int) $item);
		$this->namedtag->mData = new IntTag("mData", (int) $data);
		$this->spawnToAll();
		if($this->chunk){
			$this->chunk->setChanged();
			$this->level->clearChunkCache($this->chunk->getX(), $this->chunk->getZ());
		}
		return true;
	}

	public function getSpawnCompound(){
		return new CompoundTag("", [
			new StringTag("id", solidentity::FLOWER_POT),
			new IntTag("x", (int) $this->x),
			new IntTag("y", (int) $this->y),
			new IntTag("z", (int) $this->z),
			new ShortTag("item", (int) $this->namedtag["item"]),
			new IntTag("mData", (int) $this->namedtag["mData"])
		]);
	}
}
