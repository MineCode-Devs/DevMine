<?php



namespace devmine\events\entity;

use devmine\creatures\entities\Entity;
use devmine\inventory\items\Food;
use devmine\inventory\items\Item;

class EntityEatItemEvent extends EntityEatEvent{
	public function __construct(Entity $entity, Food $foodSource){
		parent::__construct($entity, $foodSource);
	}

	/**
	 * @return Item
	 */
	public function getResidue(){
		return parent::getResidue();
	}

	public function setResidue($residue){
		if(!($residue instanceof Item)){
			throw new \InvalidArgumentException("Eating an Item can only result in an Item residue");
		}
		parent::setResidue($residue);
	}
}
