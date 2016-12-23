<?php



namespace devmine\levels\generator\normal\biome;

use devmine\levels\generator\populator\Sugarcane;
use devmine\levels\generator\populator\TallGrass;

class OceanBiome extends WateryBiome{

	public function __construct(){
		parent::__construct();

		$sugarcane = new Sugarcane();
		$sugarcane->setBaseAmount(6);
		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(5);

		$this->addPopulator($sugarcane);
		$this->addPopulator($tallGrass);

		$this->setElevation(46, 68);

		$this->temperature = 0.5;
		$this->rainfall = 0.5;
	}

	public function getName() : string{
		return "Ocean";
	}
}
