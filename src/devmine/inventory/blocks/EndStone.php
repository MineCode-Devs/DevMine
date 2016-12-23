<?php



namespace devmine\inventory\blocks;


use devmine\inventory\items\Tool;

class EndStone extends Solid{

	protected $id = self::END_STONE;

	public function __construct(){

	}

	public function getName() : string{
		return "End Stone";
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getHardness() {
		return 3;
	}
}