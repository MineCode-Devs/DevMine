<?php



namespace devmine\inventory\items;

class Clay extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::CLAY, $meta, $count, "Clay");
	}

}

