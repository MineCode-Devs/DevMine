<?php



namespace devmine\inventory\items;

class Slimeball extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SLIMEBALL, $meta, $count, "Slimeball");
	}

}

