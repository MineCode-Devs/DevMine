<?php



namespace devmine\levels\generator\hell;

use devmine\levels\generator\biome\Biome;

class HellBiome extends Biome{

	public function getName() : string{
		return "Hell";
	}

	public function getColor(){
		return 0;
	}
}
