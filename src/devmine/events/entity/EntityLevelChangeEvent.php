<?php



namespace devmine\events\entity;

use devmine\creatures\entities\Entity;
use devmine\events;
use devmine\events\Cancellable;
use devmine\worlds\Level;

class EntityLevelChangeEvent extends EntityEvent implements Cancellable{
	public static $handlerList = null;

	private $originLevel;
	private $targetLevel;

	public function __construct(Entity $entity, Level $originLevel, Level $targetLevel){
		$this->entity = $entity;
		$this->originLevel = $originLevel;
		$this->targetLevel = $targetLevel;
	}

	public function getOrigin(){
		return $this->originLevel;
	}

	public function getTarget(){
		return $this->targetLevel;
	}
}