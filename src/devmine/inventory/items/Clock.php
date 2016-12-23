<?php



namespace devmine\inventory\items;

class Clock extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::CLOCK, $meta, $count, "Clock");
	}

}

