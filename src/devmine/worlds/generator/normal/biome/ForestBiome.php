<?php



namespace devmine\worlds\generator\normal\biome;

use devmine\inventory\blocks\Sapling;
use devmine\worlds\generator\populator\TallGrass;
use devmine\worlds\generator\populator\Tree;

class ForestBiome extends GrassyBiome{

	const TYPE_NORMAL = 0;
	const TYPE_BIRCH = 1;

	public $type;

	public function __construct($type = self::TYPE_NORMAL){
		parent::__construct();

		$this->type = $type;

		$trees = new Tree($type === self::TYPE_BIRCH ? Sapling::BIRCH : Sapling::OAK);
		$trees->setBaseAmount(5);
		$this->addPopulator($trees);

		$tallGrass = new TallGrass();
		$tallGrass->setBaseAmount(3);

		$this->addPopulator($tallGrass);

		$this->setElevation(63, 81);

		if($type === self::TYPE_BIRCH){
			$this->temperature = 0.5;
			$this->rainfall = 0.5;
		}else{
			$this->temperature = 0.7;
			$this->temperature = 0.8;
		}
	}

	public function getName(){
		return $this->type === self::TYPE_BIRCH ? "Birch Forest" : "Forest";
	}
}