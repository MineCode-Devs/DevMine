<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class BirchDoor extends Door{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::BIRCH_DOOR_BLOCK);
		parent::__construct(self::BIRCH_DOOR, 0, $count, "Birch Door");
	}
}