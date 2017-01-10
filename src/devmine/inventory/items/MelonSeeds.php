<?php



namespace devmine\inventory\items;

use devmine\inventory\blocks\Block;

class MelonSeeds extends Item{
	public function __construct($meta = 0, $count = 1){
		$this->block = Block::get(Item::MELON_STEM);
		parent::__construct(self::MELON_SEEDS, 0, $count, "Melon Seeds");
	}
}