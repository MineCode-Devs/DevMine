<?php



namespace devmine\inventory\items;


class IronAxe extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::IRON_AXE, $meta, $count, "Iron Axe");
	}

	public function isAxe(){
		return Tool::TIER_IRON;
	}

	public function getAttackDamage(){
		return 6;
	}
}