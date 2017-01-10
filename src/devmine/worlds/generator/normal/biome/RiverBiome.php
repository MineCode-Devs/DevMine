<?php



namespace devmine\worlds\generator\normal\biome;

use devmine\worlds\generator\populator\TallGrass;

class RiverBiome extends GrassyBiome{

	public function __construct(){
		parent::__construct();

		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(5);

		$this->addPopulator($tallGrass);

		$this->setElevation(58, 62);

		$this->temperature = 0.5;
		$this->rainfall = 0.7;
	}

	public function getName(){
		return "River";
	}
}