<?php



namespace devmine\levels\generator\normal\biome;

use devmine\inventory\blocks\Block;
use devmine\inventory\blocks\Flower as FlowerBlock;
use devmine\levels\generator\populator\Flower;
use devmine\levels\generator\populator\LilyPad;

class SwampBiome extends GrassyBiome{

	public function __construct(){
		parent::__construct();

		$flower = new Flower();
		$flower->setBaseAmount(8);
		$flower->addType([Block::RED_FLOWER, FlowerBlock::TYPE_BLUE_ORCHID]);

		$this->addPopulator($flower);

		$lilypad = new LilyPad();
		$lilypad->setBaseAmount(4);
		$this->addPopulator($lilypad);

		$this->setElevation(62, 63);

		$this->temperature = 0.8;
		$this->rainfall = 0.9;
	}

	public function getName() : string{
		return "Swamp";
	}

	public function getColor(){
		return 0x6a7039;
	}
}