<?php



namespace devmine\worlds\generator\normal\biome;

use devmine\inventory\blocks\Block;

abstract class SandyBiome extends NormalBiome{

	public function __construct(){
		$this->setGroundCover([
			Block::get(Block::SAND, 0),
			Block::get(Block::SAND, 0),
			Block::get(Block::SANDSTONE, 0),
			Block::get(Block::SANDSTONE, 0),
			Block::get(Block::SANDSTONE, 0),
		]);
	}
}