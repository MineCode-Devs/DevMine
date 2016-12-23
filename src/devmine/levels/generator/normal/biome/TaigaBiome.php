<?php



namespace devmine\levels\generator\normal\biome;

use devmine\inventory\blocks\Sapling;
use devmine\inventory\blocks\Block;
use devmine\levels\generator\populator\MossStone;
use devmine\levels\generator\populator\Tree;

class TaigaBiome extends SnowyBiome{

	public function __construct(){
		parent::__construct();

		$trees = new Tree(Sapling::SPRUCE);
		$trees->setBaseAmount(10);
		$this->addPopulator($trees);

		$mossStone = new MossStone();
		$mossStone->setBaseAmount(1);

		$this->addPopulator($mossStone);

		$this->setElevation(63, 81);

		$this->temperature = 0.05;
		$this->rainfall = 0.8;

		$this->setGroundCover([
			Block::get(Block::PODZOL, 0),
			Block::get(Block::PODZOL, 0),
			Block::get(Block::MOSS_STONE, 0),
			Block::get(Block::MOSS_STONE, 0),
			Block::get(Block::MOSS_STONE, 0),
		]);
	}

	public function getName() : string{
		return "Taiga";
	}
}
