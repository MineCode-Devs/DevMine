<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class Bed extends Item{

	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::BED_BLOCK);
		parent::__construct(self::BED, 0, $count, "Bed");
	}

	public function getMaxStackSize() : int{
		return 1;
	}
}