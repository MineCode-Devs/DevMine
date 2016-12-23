<?php


 
namespace devmine\inventory\blocks;

class RedSandstoneStairs extends SandstoneStairs{

	protected $id = Block::RED_SANDSTONE_STAIRS;

	public function getName() : string{
		return "Red Sandstone Stairs";
	}
}