<?php



namespace devmine\inventory\items;


class Bowl extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::BOWL, 0, $count, "Bowl");
	}

}