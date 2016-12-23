<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\levels\Level;
use devmine\Player;

class Dandelion extends Flowable{

	protected $id = self::DANDELION;

	public function __construct(){

	}

	public function getName() : string{
		return "Dandelion";
	}


	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() === 2 or $down->getId() === 3 or $down->getId() === 60){
			$this->getLevel()->setBlock($block, $this, true, true);

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
}