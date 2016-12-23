<?php



namespace devmine\levels\generator\normal\biome;

use devmine\levels\generator\biome\Biome;

abstract class NormalBiome extends Biome{

	public function getColor(){
		return $this->grassColor;
	}
}
