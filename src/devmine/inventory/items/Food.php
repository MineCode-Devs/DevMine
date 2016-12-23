<?php



namespace devmine\inventory\items;

use devmine\creatures\entities\Entity;
use devmine\creatures\entities\Human;
use devmine\server\events\entity\EntityEatItemEvent;
use devmine\server\network\protocol\EntityEventPacket;
use devmine\Player;
use devmine\Server;

abstract class Food extends Item implements FoodSource{
	public function canBeConsumed() : bool{
		return true;
	}

	public function canBeConsumedBy(Entity $entity) : bool{
		return $entity instanceof Player and ($entity->getFood() < $entity->getMaxFood()) and $this->canBeConsumed();
	}

	public function getResidue(){
		if($this->getCount() === 1){
			return Item::get(0);
		}else{
			$new = clone $this;
			$new->count--;
			return $new;
		}
	}

	public function getAdditionalEffects() : array{
		return [];
	}

	public function onConsume(Entity $human){
		$pk = new EntityEventPacket();
		$pk->eid = $human->getId();
		$pk->event = EntityEventPacket::USE_ITEM;
		if($human instanceof Player){
			$human->dataPacket($pk);
		}
		Server::broadcastPacket($human->getViewers(), $pk);

		Server::getInstance()->getPluginManager()->callEvent($ev = new EntityEatItemEvent($human, $this));
		if(!$ev->isCancelled()){
			$human->addSaturation($ev->getSaturationRestore());
			$human->addFood($ev->getFoodRestore());
			foreach($ev->getAdditionalEffects() as $effect){
				$human->addEffect($effect);
			}
			$human->getInventory()->setItemInHand($ev->getResidue());
		}
		
	}
}
