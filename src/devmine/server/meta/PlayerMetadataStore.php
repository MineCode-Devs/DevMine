<?php



namespace devmine\server\meta;

use devmine\creatures\iplayer;

class PlayerMetadataStore extends MetadataStore{

	public function disambiguate(Metadatable $player, $metadataKey){
		if(!($player instanceof IPlayer)){
			throw new \InvalidArgumentException("Argument must be an IPlayer instance");
		}

		return strtolower($player->getName()) . ":" . $metadataKey;
	}
}
