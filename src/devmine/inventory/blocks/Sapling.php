<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\worlds\generator\object\Tree;
use devmine\worlds\Level;
use devmine\creatures\player;
use devmine\utilities\main\Random;

class Sapling extends Flowable{
	const OAK = 0;
	const SPRUCE = 1;
	const BIRCH = 2;
	const JUNGLE = 3;
	const ACACIA = 4;
	const DARK_OAK = 5;

	protected $id = self::SAPLING;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function getName() : string{
		static $names = [
			0 => "Oak Sapling",
			1 => "Spruce Sapling",
			2 => "Birch Sapling",
			3 => "Jungle Sapling",
			4 => "Acacia Sapling",
			5 => "Dark Oak Sapling",
			6 => "",
			7 => "",
		];
		return $names[$this->meta & 0x07];
	}


	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() === self::GRASS or $down->getId() === self::DIRT or $down->getId() === self::FARMLAND or $down->getId() === self::PODZOL){
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}

		return false;
	}

	public function onActivate(Item $item, Player $player = null){
		if($item->getId() === Item::DYE and $item->getDamage() === 0x0F){ //Bonemeal
			//TODO: change log type
			Tree::growTree($this->getLevel(), $this->x, $this->y, $this->z, new Random(mt_rand()), $this->meta & 0x07, false);
			if(($player->gamemode & 0x01) === 0){
				$item->count--;
			}

			return true;
		}

		return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent() === true){
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}elseif($type === Level::BLOCK_UPDATE_RANDOM){ //Growth
			if(mt_rand(1, 7) === 1){
				if(($this->meta & 0x08) === 0x08){
					Tree::growTree($this->getLevel(), $this->x, $this->y, $this->z, new Random(mt_rand()), $this->meta & 0x07, false);
				}else{
					$this->meta |= 0x08;
					$this->getLevel()->setBlock($this, $this, true);

					return Level::BLOCK_UPDATE_RANDOM;
				}
			}else{
				return Level::BLOCK_UPDATE_RANDOM;
			}
		}

		return false;
	}

	public function getDrops(Item $item) : array {
		return [
			[$this->id, $this->meta & 0x07, 1],
		];
	}
}
