<?php



namespace devmine\server\events\entity;

use devmine\creatures\entities\Entity;
use devmine\server\events\Cancellable;
use devmine\inventory\items\Item;
use devmine\inventory\items\Potion;

class EntityDrinkPotionEvent extends EntityEvent implements Cancellable{
	
	public static $handlerList = null;
	
	/* @var Potion */
	private $potion;
	
	/* @var Effect[] */
	private $effects;
	
	public function __construct(Entity $entity, Potion $potion){
		$this->entity = $entity;
		$this->potion = $potion;
		$this->effects = $potion->getEffects();
	}
	
	public function getEffects(){
		return $this->effects;
	}
	
	public function getPotion(){
		return $this->potion;
	}
}
