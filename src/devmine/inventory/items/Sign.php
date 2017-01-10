<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class Sign extends Item{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::SIGN_POST);
		parent::__construct(self::SIGN, 0, $count, "Sign");
	}

	public function getMaxStackSize() : int {
		return 16;
	}
}