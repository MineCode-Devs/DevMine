<?php



namespace devmine\server\epilogos;

use devmine\creatures\entities\Entity;

class EntityepilogosStore extends epilogosStore{

	public function disambiguate(epilogosble $entity, $epilogosKey){
		if(!($entity instanceof Entity)){
			throw new \InvalidArgumentException("Argument must be an Entity instance");
		}

		return $entity->getId() . ":" . $epilogosKey;
	}
}