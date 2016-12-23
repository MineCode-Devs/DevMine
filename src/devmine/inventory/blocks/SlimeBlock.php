<?php


namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\Player;

class SlimeBlock extends Solid{

	protected $id = self::SLIME_BLOCK;

	public function __construct($meta = 15){
		$this->meta = $meta;
	}

	public function hasEntityCollision(){
		return true;
	}

	public function getHardness() {
		return 0;
	}

	public function getName() : string{
		return "Slime Block";
	}
}
