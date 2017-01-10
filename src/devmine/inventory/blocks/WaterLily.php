<?php



namespace devmine\inventory\blocks;


use devmine\inventory\items\Item;

use devmine\worlds\Level;
use devmine\server\calculations\AxisAlignedBB;
use devmine\server\calculations\Vector3;
use devmine\creatures\player;

class WaterLily extends Flowable{

	protected $id = self::WATER_LILY;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function isSolid(){
		return false;
	}

	public function getName() : string{
		return "Lily Pad";
	}

	public function getHardness() {
		return 0;
	}

	public function getResistance(){
		return 0;
	}

	public function canPassThrough(){
		return true;
	}

	protected function recalculateBoundingBox() {
		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x,
			$this->y + 0.0625,
			$this->z
		);
	}


	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($target instanceof Water){
			$up = $target->getSide(Vector3::SIDE_UP);
			if($up->getId() === Block::AIR){
				$this->getLevel()->setBlock($up, $this, true, true);
				return true;
			}
		}

		return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if(!($this->getSide(0) instanceof Water)){
				$this->getLevel()->useBreakOn($this);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}

	public function getDrops(Item $item) : array {
		return [
			[$this->id, 0, 1]
		];
	}
}