<?php



namespace devmine\inventory\blocks;

class LightWeightedPressurePlate extends PressurePlate{
	protected $id = self::LIGHT_WEIGHTED_PRESSURE_PLATE;

	public function getName() : string{
		return "Light Weighted Pressure Plate";
	}
}