<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class PumpkinSeeds extends Item{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::PUMPKIN_STEM);
		parent::__construct(self::PUMPKIN_SEEDS, 0, $count, "Pumpkin Seeds");
	}
}