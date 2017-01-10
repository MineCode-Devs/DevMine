<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class JungleDoor extends Door{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::JUNGLE_DOOR_BLOCK);
		parent::__construct(self::JUNGLE_DOOR, 0, $count, "Jungle Door");
	}
}