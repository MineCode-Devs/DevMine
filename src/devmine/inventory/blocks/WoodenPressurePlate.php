<?php



namespace devmine\inventory\blocks;

class WoodenPressurePlate extends PressurePlate{
	protected $id = self::WOODEN_PRESSURE_PLATE;

	public function getName() : string{
		return "Wooden Pressure Plate";
	}
}