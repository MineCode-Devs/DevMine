<?php



namespace devmine\inventory\items;


class Bow extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BOW, $meta, $count, "Bow");
	}

}