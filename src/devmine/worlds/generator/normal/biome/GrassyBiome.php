<?php



namespace devmine\worlds\generator\normal\biome;

use devmine\inventory\blocks\Block;

abstract class GrassyBiome extends NormalBiome{

	public function __construct(){
		$this->setGroundCover([
			Block::get(Block::GRASS, 0),
			Block::get(Block::DIRT, 0),
			Block::get(Block::DIRT, 0),
			Block::get(Block::DIRT, 0),
			Block::get(Block::DIRT, 0),
		]);
	}
}