<?php



namespace devmine\server\events\entity;

use devmine\inventory\blocks\Block;
use devmine\creatures\entities\Entity;
use devmine\server\events\Cancellable;
use devmine\inventory\items\FoodSource;

class EntityEatBlockEvent extends EntityEatEvent implements Cancellable{
	public function __construct(Entity $entity, FoodSource $foodSource){
		if(!($foodSource instanceof Block)){
			throw new \InvalidArgumentException("Food source must be a block");
		}
		parent::__construct($entity, $foodSource);
	}

	/**
	 * @return Block
	 */
	public function getResidue(){
		return parent::getResidue();
	}

	public function setResidue($residue){
		if(!($residue instanceof Block)){
			throw new \InvalidArgumentException("Eating a Block can only result in a Block residue");
		}
		parent::setResidue($residue);
	}
}
