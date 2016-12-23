<?php



namespace devmine\inventory\items;


class Snowball extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SNOWBALL, 0, $count, "Snowball");
	}

	public function getMaxStackSize() : int {
		return 16;
	}

}