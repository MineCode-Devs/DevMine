<?php



namespace devmine\inventory\items;


class StonePickaxe extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::STONE_PICKAXE, $meta, $count, "Stone Pickaxe");
	}

	public function isPickaxe(){
		return Tool::TIER_STONE;
	}

	public function getAttackDamage(){
		return 4;
	}
}
