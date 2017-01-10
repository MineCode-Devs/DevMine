<?php



namespace devmine\inventory\blocks;

class BirchWoodStairs extends WoodStairs{

	protected $id = self::BIRCH_WOOD_STAIRS;

	public function getName() : string{
		return "Birch Wood Stairs";
	}
}