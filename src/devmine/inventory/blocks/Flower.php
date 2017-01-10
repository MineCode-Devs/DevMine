<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\worlds\Level;
use devmine\server\calculations\Vector3;
use devmine\creatures\player;

class Flower extends Flowable{
	const TYPE_POPPY = 0;
	const TYPE_BLUE_ORCHID = 1;
	const TYPE_ALLIUM = 2;
	const TYPE_AZURE_BLUET = 3;
	const TYPE_RED_TULIP = 4;
	const TYPE_ORANGE_TULIP = 5;
	const TYPE_WHITE_TULIP = 6;
	const TYPE_PINK_TULIP = 7;
	const TYPE_OXEYE_DAISY = 8;

	protected $id = self::RED_FLOWER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		static $names = [
			self::TYPE_POPPY => "Poppy",
			self::TYPE_BLUE_ORCHID => "Blue Orchid",
			self::TYPE_ALLIUM => "Allium",
			self::TYPE_AZURE_BLUET => "Azure Bluet",
			self::TYPE_RED_TULIP => "Red Tulip",
			self::TYPE_ORANGE_TULIP => "Orange Tulip",
			self::TYPE_WHITE_TULIP => "White Tulip",
			self::TYPE_PINK_TULIP => "Pink Tulip",
			self::TYPE_OXEYE_DAISY => "Oxeye Daisy",
			9 => "Unknown",
			10 => "Unknown",
			11 => "Unknown",
			12 => "Unknown",
			13 => "Unknown",
			14 => "Unknown",
			15 => "Unknown"
		];
		return $names[$this->meta];
	}


	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() === Block::GRASS or $down->getId() === Block::DIRT or $down->getId() === Block::FARMLAND){
			$this->getLevel()->setBlock($block, $this, true);

			return true;
		}

		return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(Vector3::SIDE_DOWN)->isTransparent()){
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}
}