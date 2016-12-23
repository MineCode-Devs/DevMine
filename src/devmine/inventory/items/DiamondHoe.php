<?php



namespace devmine\inventory\items;


class DiamondHoe extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::DIAMOND_HOE, $meta, $count, "Diamond Hoe");
	}

	public function isHoe(){
		return Tool::TIER_DIAMOND;
	}
}