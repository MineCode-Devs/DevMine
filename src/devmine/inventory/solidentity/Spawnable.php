<?php


namespace devmine\inventory\solidentity;

use devmine\worlds\format\Chunk;
use devmine\creatures\player\NBT;
use devmine\creatures\player\tag\CompoundTag;
use devmine\server\network\protocol\BlockEntityDataPacket;
use devmine\creatures\player;

abstract class Spawnable extends Tile{
	
	public function spawnTo(Player $player){
		if($this->closed){
			return false;
		}
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		$nbt->setData($this->getSpawnCompound());
		$pk = new BlockEntityDataPacket();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->namedtag = $nbt->write(true);
		$player->dataPacket($pk);
		return true;
	}
	
	/**
	 * @return CompoundTag
	 */
	public abstract function getSpawnCompound();
	
	public function __construct(Chunk $chunk, CompoundTag $nbt){
		parent::__construct($chunk, $nbt);
		$this->spawnToAll();
	}
	
	public function spawnToAll(){
		if($this->closed){
			return;
		}
		foreach($this->getLevel()->getChunkPlayers($this->chunk->getX(), $this->chunk->getZ()) as $player){
			if($player->spawned === true){
				$this->spawnTo($player);
			}
		}
	}
	
	protected function onChanged(){
		$this->spawnToAll();
		if($this->chunk !== null){
			$this->chunk->setChanged();
			$this->level->clearChunkCache($this->chunk->getX(), $this->chunk->getZ());
		}
	}
}
