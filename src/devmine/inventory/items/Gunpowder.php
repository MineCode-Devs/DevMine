<?php



namespace devmine\inventory\items;

class Gunpowder extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GUNPOWDER, $meta, $count, "Gunpowder");
	}

}

