<?php



namespace devmine\events\entity;

use devmine\creatures\entities\Entity;
use devmine\events;
use devmine\events\Cancellable;
use devmine\creatures\entities\Effect;

class EntityEffectAddEvent extends EntityEvent implements Cancellable{

	public static $handlerList = null;

	/** @var Effect */
	protected $effect;

	public function __construct(Entity $entity, Effect $effect){
		$this->entity = $entity;
		$this->effect = $effect;
	}

	/**
	 * @return Effect
	 */
	public function getEffect(){
		return $this->effect;
	}
}
