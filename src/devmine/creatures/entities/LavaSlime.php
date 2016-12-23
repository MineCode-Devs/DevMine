<?php



namespace devmine\creatures\entities;

use devmine\server\network\protocol\AddEntityPacket;
use devmine\Player;

class LavaSlime extends Living{
	const NETWORK_ID = 42;

	const DATA_SLIME_SIZE = 16;

	public $dropExp = [1, 4];
	
	public function getName() : string{
		return "LavaSlime";
	}
	
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = LavaSlime::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->epilogos = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}
}