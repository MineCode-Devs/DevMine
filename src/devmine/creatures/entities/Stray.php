<?php




namespace devmine\creatures\entities;

use devmine\server\network\protocol\AddEntityPacket;
use devmine\Player;
use devmine\server\network\protocol\MobEquipmentPacket;
use devmine\inventory\items\Item as ItemItem;

class Stray extends Skeleton{
	const NETWORK_ID = 46;

	public $dropExp = [5, 5];
	
	public function getName() : string{
		return "Stray";
	}
	
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Stray::NETWORK_ID;
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
		
		$pk = new MobEquipmentPacket();
		$pk->eid = $this->getId();
		$pk->item = new ItemItem(ItemItem::BOW);
		$pk->slot = 0;
		$pk->selectedSlot = 0;

		$player->dataPacket($pk);
	}
}