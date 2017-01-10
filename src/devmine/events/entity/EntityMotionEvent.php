<?php



namespace devmine\events\entity;

use devmine\creatures\entities\Entity;
use devmine\events;
use devmine\events\Cancellable;
use devmine\server\calculations\Vector3;

class EntityMotionEvent extends EntityEvent implements Cancellable{
	public static $handlerList = null;

	/** @var Vector3 */
	private $mot;

	public function __construct(Entity $entity, Vector3 $mot){
		$this->entity = $entity;
		$this->mot = $mot;
	}

	/**
	 * @return Vector3
	 */
	public function getVector(){
		return $this->mot;
	}


}
