<?php



namespace devmine\inventory\items;


class PrismarineCrystals extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::PRISMARINE_CRYSTALS, $meta, $count, "Prismarine Crystals");
	}
}