<?php



namespace devmine\worlds\generator\normal\biome;

use devmine\worlds\generator\populator\TallGrass;

class IcePlainsBiome extends SnowyBiome{

	public function __construct(){
		parent::__construct();

		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(5);

		$this->addPopulator($tallGrass);

		$this->setElevation(63, 74);

		$this->temperature = 0.05;
		$this->rainfall = 0.8;
	}

	public function getName(){
		return "Ice Plains";
	}
}