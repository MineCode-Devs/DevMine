<?php



namespace devmine\inventory\blocks;

class BurningFurnace extends Furnace implements SolidLight{

	protected $id = self::BURNING_FURNACE;

	public function getName() : string{
		return "Burning Furnace";
	}

	public function getLightLevel(){
		return 13;
	}
}