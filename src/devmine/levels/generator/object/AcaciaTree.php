<?php



namespace devmine\levels\generator\object;

use devmine\inventory\blocks\Block;
use devmine\inventory\blocks\Leaves2;
use devmine\inventory\blocks\Wood2;
use devmine\levels\ChunkManager;
use devmine\utilities\main\Random;

class AcaciaTree extends Tree{
	public function __construct(){
		$this->trunkBlock = Block::WOOD2;
		$this->leafBlock = Block::LEAVES2;
		$this->leafType = Leaves2::ACACIA;
		$this->type = Wood2::ACACIA;
		$this->treeHeight = 8;
	}

	/*public function placeObject(ChunkManager $level, $x, $y, $z, Random $random){
	}*/
	//TODO: rewrite
}