<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\worlds\Level;
use devmine\creatures\player;

class TallGrass extends Flowable{
	
	const NORMAL = 1;
	const FERN = 2;

	protected $id = self::TALL_GRASS;

	public function __construct($meta = 1){
		$this->meta = $meta;
	}

	public function canBeReplaced(){
		return true;
	}

	public function getName() : string{
		static $names = [
			0 => "Dead Shrub",
			1 => "Tall Grass",
			2 => "Fern",
			3 => ""
		];
		return $names[$this->meta & 0x03];
	}

	public function getBurnChance() : int{
		return 60;
	}

	public function getBurnAbility() : int{
		return 100;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() === self::GRASS){
			$this->getLevel()->setBlock($block, $this, true);

			return true;
		}

		return false;
	}


	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent() === true){ //Replace with common break method
				$this->getLevel()->setBlock($this, new Air(), true, true);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}

	public function getToolType()
	{
		return Tool::TYPE_SHEARS;
	}

	public function getDrops(Item $item) : array {
		if(mt_rand(0, 15) === 0){
			return [
				[Item::WHEAT_SEEDS, 0, 1]
			];
		}

		return [];
	}

}
