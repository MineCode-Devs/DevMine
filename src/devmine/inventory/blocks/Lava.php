<?php



namespace devmine\inventory\blocks;

use devmine\creatures\entities\Effect;
use devmine\creatures\entities\Entity;
use devmine\server\events\entity\EntityCombustByBlockEvent;
use devmine\server\events\entity\EntityDamageByBlockEvent;
use devmine\server\events\entity\EntityDamageEvent;
use devmine\inventory\items\Item;
use devmine\Player;
use devmine\Server;

class Lava extends Liquid{

	protected $id = self::LAVA;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getLightLevel(){
		return 15;
	}

	public function getName() : string{
		return "Lava";
	}

	public function onEntityCollide(Entity $entity){
		$entity->fallDistance *= 0.5;
		$ProtectL = 0;
		if(!$entity->hasEffect(Effect::FIRE_RESISTANCE)){
			$ev = new EntityDamageByBlockEvent($this, $entity, EntityDamageEvent::CAUSE_LAVA, 4);
			if($entity->attack($ev->getFinalDamage(), $ev) === true){
				$ev->useArmors();
			}
			$ProtectL = $ev->getFireProtectL();
		}

		$ev = new EntityCombustByBlockEvent($this, $entity, 15, $ProtectL);
		Server::getInstance()->getPluginManager()->callEvent($ev);
		if(!$ev->isCancelled()){
			$entity->setOnFire($ev->getDuration());
		}

		$entity->resetFallDistance();
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$ret = $this->getLevel()->setBlock($this, $this, true, false);
		$this->getLevel()->scheduleUpdate($this, $this->tickRate());

		return $ret;
	}

}
