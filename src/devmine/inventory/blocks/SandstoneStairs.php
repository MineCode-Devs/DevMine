<?php



namespace devmine\inventory\blocks;


use devmine\inventory\items\Tool;

class SandstoneStairs extends Stair{

	protected $id = self::SANDSTONE_STAIRS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness() {
		return 0.8;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getName() : string{
		return "Sandstone Stairs";
	}

}