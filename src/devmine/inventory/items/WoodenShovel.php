<?php



namespace devmine\inventory\items;


class WoodenShovel extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::WOODEN_SHOVEL, $meta, $count, "Wooden Shovel");
	}

	public function isShovel(){
		return Tool::TIER_WOODEN;
	}

	public function getAttackDamage(){
		return 2;
	}
}
