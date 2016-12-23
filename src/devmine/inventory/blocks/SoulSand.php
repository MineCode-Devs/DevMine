<?php



namespace devmine\inventory\blocks;


use devmine\inventory\items\Tool;
use devmine\server\calculations\AxisAlignedBB;

class SoulSand extends Solid{

	protected $id = self::SOUL_SAND;

	public function __construct(){

	}

	public function getName() : string{
		return "Soul Sand";
	}

	public function getHardness() {
		return 0.5;
	}

	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	protected function recalculateBoundingBox() {

		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 1 - 0.125,
			$this->z + 1
		);
	}

}