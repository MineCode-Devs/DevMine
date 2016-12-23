<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class ItemFrame extends Item{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::ITEM_FRAME_BLOCK);
		parent::__construct(self::ITEM_FRAME, 0, $count, "Item Frame");
	}
}