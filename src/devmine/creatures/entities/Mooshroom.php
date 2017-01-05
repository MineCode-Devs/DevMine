<?php



namespace devmine\creatures\entities;

use devmine\inventory\items\enchantment\Enchantment;
use devmine\server\network\protocol\AddEntityPacket;
use devmine\Player;
use devmine\server\events\entity\EntityDamageByEntityEvent;
use devmine\inventory\items\Item as ItemItem;

class Mooshroom extends Animal{
	const NETWORK_ID = 16;

	public $width = 0.3;
	public $length = 0.9;
	public $height = 1.8;
	
	public function getName() : string{
		return "Mooshroom";
	}
	
	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = Mooshroom::NETWORK_ID;
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
		$lootingL = 0;
		$cause = $this->lastDamageCause;
		if($cause instanceof EntityDamageByEntityEvent and $cause->getDamager() instanceof Player){
			$lootingL = $cause->getDamager()->getItemInHand()->getEnchantmentLevel(Enchantment::TYPE_WEAPON_LOOTING);
		}
		$drops = array(ItemItem::get(ItemItem::RAW_BEEF, 0, mt_rand(1, 3 + $lootingL)));
		$drops[] = ItemItem::get(ItemItem::LEATHER, 0, mt_rand(0, 2 + $lootingL));
		return $drops;
	}
}