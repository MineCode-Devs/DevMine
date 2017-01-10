<?php



/**
 * Entity related Events, like spawn, inventory, attack...
 */
namespace devmine\events\entity;

use devmine\events\Event;

abstract class EntityEvent extends Event{
	/** @var \devmine\creatures\entities\Entity */
	protected $entity;

	public function getEntity(){
		return $this->entity;
	}
}