<?php



namespace devmine\inventory\items;


class StoneAxe extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STONE_AXE, $meta, $count, "Stone Axe");
	}

	public function isAxe(){
		return Tool::TIER_STONE;
	}

	public function getAttackDamage(){
		return 5;
	}
}