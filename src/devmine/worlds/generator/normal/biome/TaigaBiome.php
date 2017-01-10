<?php



namespace devmine\worlds\generator\normal\biome;

use devmine\inventory\blocks\Sapling;
use devmine\worlds\generator\populator\TallGrass;
use devmine\worlds\generator\populator\Tree;

class TaigaBiome extends SnowyBiome{

	public function __construct(){
		parent::__construct();

		$trees = new Tree(Sapling::SPRUCE);
		$trees->setBaseAmount(10);
		$this->addPopulator($trees);

		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(1);

		$this->addPopulator($tallGrass);

		$this->setElevation(63, 81);

		$this->temperature = 0.05;
		$this->rainfall = 0.8;
	}

	public function getName(){
		return "Taiga";
	}
}