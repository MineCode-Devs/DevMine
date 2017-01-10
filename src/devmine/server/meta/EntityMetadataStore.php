<?php



namespace devmine\server\meta;

use devmine\creatures\entities\Entity;

class EntityMetadataStore extends MetadataStore{

	public function disambiguate(Metadatable $entity, $metadataKey){
		if(!($entity instanceof Entity)){
			throw new \InvalidArgumentException("Argument must be an Entity instance");
		}

		return $entity->getId() . ":" . $metadataKey;
	}
}