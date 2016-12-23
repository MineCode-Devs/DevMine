<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\levels\Level;
use devmine\Player;

class RedMushroom extends Flowable{

	protected $id = self::RED_MUSHROOM;

	public function __construct(){

	}

	public function getName() : string{
		return "Red Mushroom";
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

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->isTransparent() === false){
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}

		return false;
	}
}