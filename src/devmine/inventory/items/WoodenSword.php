<?php



namespace devmine\inventory\items;


class WoodenSword extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::WOODEN_SWORD, $meta, $count, "Wooden Sword");
	}

	public function isSword(){
		return Tool::TIER_WOODEN;
	}

	public function getAttackDamage(){
		return 5;
	}
}
