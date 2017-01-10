<?php



 namespace devmine\inventory\solidentity;
 
 use devmine\worlds\format\Chunk;
 use devmine\creatures\player\tag\CompoundTag;
 use devmine\creatures\player\tag\IntTag;
 use devmine\creatures\player\tag\StringTag;
 use devmine\creatures\player\tag\ByteTag;
 use devmine\creatures\player\tag\FloatTag;
 
 class Beacon extends Spawnable{
 
 	public function __construct(Chunk $chunk, CompoundTag $nbt){
 		parent::__construct($chunk, $nbt);
 	}
 
 	public function saveNBT(){
 		parent::saveNBT();
 	}
 
 	private function setChanged(){
 		$this->spawnToAll();
 		if($this->chunk instanceof Chunk){
 			$this->chunk->setChanged();
 			$this->level->clearChunkCache($this->chunk->getX(), $this->chunk->getZ());
 		}
 	}
 
 	public function getSpawnCompound(){
 		return new CompoundTag("", [
 			new StringTag("id", Tile::BEACON),
 			new IntTag("x", (int) $this->x),
 			new IntTag("y", (int) $this->y),
 			new IntTag("z", (int) $this->z)
 		]);
 	}
 }

