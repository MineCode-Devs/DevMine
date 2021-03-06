<?php



namespace devmine\creatures\entities;

use devmine\creatures\player;
use devmine\server\network\protocol\AddEntityPacket;

class EnderDragon extends Monster {

	const NETWORK_ID = 53;

	//----- This cord from Dragon.php -----//
	public $dropExp = [500, 12,000];//TODO: Add death animation and exp drop.

	public function initEntity(){
		$this->setMaxHealth(200);
		parent::initEntity();
	}
	//-------------------------------------//

	public function getName() : string{
		return "Ender Dragon";
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = self::NETWORK_ID;
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

}