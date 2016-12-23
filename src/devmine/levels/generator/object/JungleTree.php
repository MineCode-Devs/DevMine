<?php



namespace devmine\levels\generator\object;

use devmine\inventory\blocks\Block;
use devmine\inventory\blocks\Leaves;
use devmine\inventory\blocks\Wood;

class JungleTree extends Tree{

	public function __construct(){
		$this->trunkBlock = Block::LOG;
		$this->leafBlock = Block::LEAVES;
		$this->leafType = Leaves::JUNGLE;
		$this->type = Wood::JUNGLE;
		$this->treeHeight = 8;
	}
}