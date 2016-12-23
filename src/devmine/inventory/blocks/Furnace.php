<?php



namespace devmine\inventory\blocks;


class Furnace extends BurningFurnace{

	protected $id = self::FURNACE;

	public function getName() : string{
		return "Furnace";
	}
}