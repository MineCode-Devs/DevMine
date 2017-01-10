<?php



namespace devmine\inventory\items;


class Shears extends Tool{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SHEARS, $meta, $count, "Shears");
	}
}