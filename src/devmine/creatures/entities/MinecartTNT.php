<?php



namespace devmine\creatures\entities;

use devmine\server\network\protocol\AddEntityPacket;
use devmine\Player;

class MinecartTNT extends Minecart{
	const NETWORK_ID = 97;

	public function getName() : string{
		return "Minecart TNT";
	}

	public function getType() : int{
		return self::TYPE_TNT;
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = MinecartTNT::NETWORK_ID;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = 0;
		$pk->speedY = 0;
		$pk->speedZ = 0;
		$pk->yaw = 0;
		$pk->pitch = 0;
		$pk->epilogos = $this->dataProperties;
		$player->dataPacket($pk);

		Entity::spawnTo($player);
	}
}