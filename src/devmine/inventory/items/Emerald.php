<?php



namespace devmine\inventory\items;

class Emerald extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::EMERALD, $meta, $count, "Emerald");
	}

}

