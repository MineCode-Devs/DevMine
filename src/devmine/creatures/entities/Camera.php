<?php



namespace devmine\creatures\entities;

use devmine\server\network\protocol\AddEntityPacket;
use devmine\creatures\player;
use devmine\events\entity\EntityDamageByEntityEvent;
use devmine\inventory\items\Item as ItemItem;

class Camera extends Living{
	const NETWORK_ID = 62;

	public $width = 1;
	public $length = 2;
	public $height = 2;
	
	public function getName() : string{
		return "Camera";
	}
	
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Camera::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);
		parent::spawnTo($player);
	}
	
	public function getDrops(){
		return [];
	}
}
