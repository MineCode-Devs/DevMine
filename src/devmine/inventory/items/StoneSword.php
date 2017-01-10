<?php



namespace devmine\inventory\items;


class StoneSword extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STONE_SWORD, $meta, $count, "Stone Sword");
	}

	public function isSword(){
		return Tool::TIER_STONE;
	}

	public function getAttackDamage(){
		return 6;
	}
}
