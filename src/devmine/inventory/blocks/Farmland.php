<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\server\calculations\AxisAlignedBB;

class Farmland extends Solid{

	protected $id = self::FARMLAND;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Farmland";
	}

	public function getHardness() {
		return 0.6;
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
			$this->y + 0.9375,
			$this->z + 1
		);
	}

	public function getDrops(Item $item) : array {
		return [
			[Item::DIRT, 0, 1],
		];
	}
}