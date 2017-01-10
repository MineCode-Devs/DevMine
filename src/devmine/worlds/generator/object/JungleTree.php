<?php



namespace devmine\worlds\generator\object;

use devmine\inventory\blocks\Block;
use devmine\inventory\blocks\Wood;

class JungleTree extends Tree{

	public function __construct(){
		$this->trunkBlock = Block::LOG;
		$this->leafBlock = Block::LEAVES;
		$this->type = Wood::JUNGLE;
		$this->treeHeight = 8;
	}
}