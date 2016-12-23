<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class FlowerPot extends Item {
	public function __construct($meta = 0, $count = 1) {
		$this->block = Block::get(Item::FLOWER_POT_BLOCK);
		parent::__construct(self::FLOWER_POT, 0, $count, "Flower Pot");
	}

	public function getMaxStackSize() : int{
		return 64;
	}
} 
