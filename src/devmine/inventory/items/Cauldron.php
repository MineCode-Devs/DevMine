<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class Cauldron extends Item{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Block::CAULDRON_BLOCK);
		parent::__construct(self::CAULDRON, $meta, $count, "Cauldron");
	}

	public function getMaxStackSize() : int{
		return 1;
	}
}