<?php

/**
 * Opendevmine Project
 *
 * @author PeratX
 */

namespace devmine\creatures\entities;

use devmine\server\network\protocol\AddEntityPacket;
use devmine\Player;

class Wolf extends Animal{
	const NETWORK_ID = 14;

	public $width = 0.3;
	public $length = 0.9;
	public $height = 1.8;

	public $dropExp = [1, 3];
	
	public function getName() : string{
		return "Wolf";
	}
	
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Wolf::NETWORK_ID;
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