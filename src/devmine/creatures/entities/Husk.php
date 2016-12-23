<?php



namespace devmine\creatures\entities;

use devmine\Player;
use devmine\server\calculations\Vector3;
use devmine\server\network\protocol\AddEntityPacket;

class Husk extends Zombie{
	const NETWORK_ID = 47;
	
	public function getName(){
		return "Husk";
	}
	
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Husk::NETWORK_ID;
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

		Entity::spawnTo($player);
	}
}