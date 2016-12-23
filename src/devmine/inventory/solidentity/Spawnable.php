<?php



namespace devmine\inventory\solidentity;

use devmine\levels\format\FullChunk;
use devmine\creatures\player\NBT;
use devmine\creatures\player\tag\CompoundTag;
use devmine\server\network\Network;
use devmine\server\network\protocol\BlockEntityDataPacket;
use devmine\Player;

abstract class Spawnable extends solidentity{

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
		$pk->namedtag = $nbt->write();
		$player->dataPacket($pk);

		return true;
	}

	/**
	 * @return CompoundTag
	 */
	public abstract function getSpawnCompound();

	public function __construct(FullChunk $chunk, CompoundTag $nbt){
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
}
