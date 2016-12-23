<?php



namespace devmine\creatures\entities;

use devmine\server\network\protocol\AddEntityPacket;
use devmine\Player;
use devmine\server\events\entity\EntityDamageByEntityEvent;
use devmine\inventory\items\enchantment\Enchantment;
use devmine\inventory\items\Item as ItemItem;

class Blaze extends Monster{
	const NETWORK_ID = 43;

	public $width = 0.3;
	public $length = 0.9;
	public $height = 1.8;

	public $dropExp = [10, 10];
	
	public function getName() : string{
		return "Blaze";
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
		$pk->epilogos = $this->dataProperties;
		$player->dataPacket($pk);
		parent::spawnTo($player);
	}

	public function getDrops(){
		$cause = $this->lastDamageCause;
		//Only drop when kill by player or dog(No add now.)
		if($cause instanceof EntityDamageByEntityEvent and $cause->getDamager() instanceof Player){
			$lootingL = $cause->getDamager()->getItemInHand()->getEnchantmentLevel(Enchantment::TYPE_WEAPON_LOOTING);
			$drops = array(ItemItem::get(ItemItem::BLAZE_ROD, 0, mt_rand(0, 1 + $lootingL)));
			return $drops;
		}
		return [];
	}
}