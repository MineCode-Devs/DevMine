<?php



namespace devmine\inventory\blocks;

class SpruceWoodStairs extends WoodStairs{

	protected $id = self::SPRUCE_WOOD_STAIRS;

	public function getName() : string{
		return "Spruce Wood Stairs";
	}
}