<?php



namespace devmine\worlds\generator\object;

use devmine\inventory\blocks\Block;
use devmine\inventory\blocks\Wood;
use devmine\worlds\ChunkManager;
use devmine\utilities\main\Random;

class BirchTree extends Tree{

	protected $superBirch = false;

	public function __construct($superBirch = false){
		$this->trunkBlock = Block::LOG;
		$this->leafBlock = Block::LEAVES;
		$this->type = Wood::BIRCH;
		$this->superBirch = (bool) $superBirch;
	}

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random){
		$this->treeHeight = $random->nextBoundedInt(3) + 5;
		if($this->superBirch){
			$this->treeHeight += 5;
		}
		parent::placeObject($level, $x, $y, $z, $random);
	}
}