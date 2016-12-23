<?php



namespace devmine\inventory\items;

class GoldenCarrot extends Food{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLDEN_CARROT, $meta, $count, "Golden Carrot");
	}

	public function getFoodRestore() : int{
		return 6;
	}

	public function getSaturationRestore() : float{
		return 14.4;
	}
}
