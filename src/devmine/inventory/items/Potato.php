<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class Potato extends Item{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::POTATO_BLOCK);
		parent::__construct(self::POTATO, 0, $count, "Potato");
	}
}