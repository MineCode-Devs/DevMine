<?php



namespace devmine\inventory\blocks;

use devmine\levels\Level;
use devmine\inventory\items\Item;
use devmine\Player;

class DeadBush extends Flowable{

	protected $id = self::DEAD_BUSH;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Dead Bush";
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() === Block::SAND or $down->getId() === Block::PODZOL or
			$down->getId() === Block::HARDENED_CLAY or $down->getId() === Block::STAINED_CLAY){
			$this->getLevel()->setBlock($block, $this, true);
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
		}

		return false;
	}


	public function getDrops(Item $item) : array {
		if($item->isShears()){
			return [
				[Item::DEAD_BUSH, 0, 1],
			];
		}else{
			return [
				[Item::STICK, 0, mt_rand(0, 2)],
			];
		}
		
	}

}
