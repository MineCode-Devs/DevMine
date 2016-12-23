<?php



namespace devmine\server\epilogos;

use devmine\levels\Level;

class LevelepilogosStore extends epilogosStore{

	public function disambiguate(epilogosble $level, $epilogosKey){
		if(!($level instanceof Level)){
			throw new \InvalidArgumentException("Argument must be a Level instance");
		}

		return strtolower($level->getName()) . ":" . $epilogosKey;
	}
}