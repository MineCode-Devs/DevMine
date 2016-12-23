<?php



namespace devmine\inventory\blocks;

class HeavyWeightedPressurePlate extends PressurePlate{
	protected $id = self::HEAVY_WEIGHTED_PRESSURE_PLATE;

	public function getName() : string{
		return "Heavy Weighted Pressure Plate";
	}
}