<?php



namespace devmine\worlds\generator\object;

use devmine\inventory\blocks\Block;
use devmine\inventory\blocks\Wood;
use devmine\worlds\ChunkManager;
use devmine\utilities\main\Random;

class OakTree extends Tree{

	public function __construct(){
		$this->trunkBlock = Block::LOG;
		$this->leafBlock = Block::LEAVES;
		$this->type = Wood::OAK;
	}

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random){
		$this->treeHeight = $random->nextBoundedInt(3) + 4;
		parent::placeObject($level, $x, $y, $z, $random);
	}
}