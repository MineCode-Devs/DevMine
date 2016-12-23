<?php



namespace devmine\inventory\blocks;


class Sponge extends Solid{

	protected $id = self::SPONGE;

	public function __construct(){

	}

	public function getHardness() {
		return 0.6;
	}

	public function getName() : string{
		return "Sponge";
	}

}