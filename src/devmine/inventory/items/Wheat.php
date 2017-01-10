<?php



namespace devmine\inventory\items;

class Wheat extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::WHEAT, $meta, $count, "Wheat");
	}

}

