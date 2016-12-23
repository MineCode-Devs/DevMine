<?php
namespace devmine\inventory\items;

class EnchantingBottle extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::ENCHANTING_BOTTLE, $meta, $count, "Bottle o' Enchanting");
	}
}