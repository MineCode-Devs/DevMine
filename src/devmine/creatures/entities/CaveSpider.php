<?php

/**
 * Opendevmine Project
 *
 * @author PeratX
 */

namespace devmine\creatures\entities;

use devmine\server\network\protocol\AddEntityPacket;
use devmine\Player;

class CaveSpider extends Monster{
	const NETWORK_ID = 40;

	public $width = 1;
	public $length = 1;
	public $height = 0.5;

	public $dropExp = [5, 5];

	public function getName() : string{
		return "Cave Spider";
	}
	
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = CaveSpider::NETWORK_ID;
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