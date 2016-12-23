<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class WoodenDoor extends Door{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::WOODEN_DOOR_BLOCK);
		parent::__construct(self::WOODEN_DOOR, 0, $count, "Wooden Door");
	}
}