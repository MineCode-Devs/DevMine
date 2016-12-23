<?php



namespace devmine\levels\generator\object;

use devmine\inventory\blocks\Block;
use devmine\inventory\blocks\Leaves2;
use devmine\inventory\blocks\Wood2;

class DarkOakTree extends Tree{
	public function __construct(){
		$this->trunkBlock = Block::WOOD2;
		$this->leafBlock = Block::LEAVES2;
		$this->leafType = Leaves2::DARK_OAK;
		$this->type = Wood2::DARK_OAK;
		$this->treeHeight = 8;
	}
}