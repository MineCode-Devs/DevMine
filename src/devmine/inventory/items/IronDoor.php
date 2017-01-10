<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class IronDoor extends Door{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::IRON_DOOR_BLOCK);
		parent::__construct(self::IRON_DOOR, 0, $count, "Iron Door");
	}
}