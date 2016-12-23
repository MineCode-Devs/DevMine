<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class Cake extends Item{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::CAKE_BLOCK);
		parent::__construct(self::CAKE, 0, $count, "Cake");
	}

	public function getMaxStackSize() : int{
		return 1;
	}
}