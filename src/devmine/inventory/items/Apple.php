<?php



namespace devmine\inventory\items;


class Apple extends Food{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::APPLE, 0, $count, "Apple");
	}

	public function getFoodRestore() : int{
		return 4;
	}

	public function getSaturationRestore() : float{
		return 2.4;
	}
}