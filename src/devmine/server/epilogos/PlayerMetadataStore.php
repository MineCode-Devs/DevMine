<?php



namespace devmine\server\epilogos;

use devmine\IPlayer;

class PlayerepilogosStore extends epilogosStore{

	public function disambiguate(epilogosble $player, $epilogosKey){
		if(!($player instanceof IPlayer)){
			throw new \InvalidArgumentException("Argument must be an IPlayer instance");
		}

		return strtolower($player->getName()) . ":" . $epilogosKey;
	}
}
