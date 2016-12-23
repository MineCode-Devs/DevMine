<?php



namespace devmine\inventory\items;


class GoldShovel extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GOLD_SHOVEL, $meta, $count, "Gold Shovel");
	}

	public function isShovel(){
		return Tool::TIER_GOLD;
	}

	public function getAttackDamage(){
		return 2;
	}
}
