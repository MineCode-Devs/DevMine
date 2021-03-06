<?php



namespace devmine\inventory\items;


class WoodenAxe extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::WOODEN_AXE, $meta, $count, "Wooden Axe");
	}

	public function isAxe(){
		return Tool::TIER_WOODEN;
	}

	public function getAttackDamage(){
		return 4;
	}
}
