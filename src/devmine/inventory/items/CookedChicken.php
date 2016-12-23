<?php



namespace devmine\inventory\items;

class CookedChicken extends Food{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::COOKED_CHICKEN, $meta, $count, "Cooked Chicken");
	}

	public function getFoodRestore() : int{
		return 6;
	}

	public function getSaturationRestore() : float{
		return 7.2;
	}
}

