<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class AcaciaDoor extends Door{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::ACACIA_DOOR_BLOCK);
		parent::__construct(self::ACACIA_DOOR, 0, $count, "Acacia Door");
	}
}