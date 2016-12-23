<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class SpruceDoor extends Door{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::SPRUCE_DOOR_BLOCK);
		parent::__construct(self::SPRUCE_DOOR, 0, $count, "Spruce Door");
	}
}