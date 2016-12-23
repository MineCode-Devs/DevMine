<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;

class UnpoweredRepeater extends PoweredRepeater{
	protected $id = self::UNPOWERED_REPEATER_BLOCK;

	public function getName() : string{
		return "Unpowered Repeater";
	}

	public function getStrength(){
		return 0;
	}

	public function isActivated(Block $from = null){
		return false;
	}

	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air(), true);
	}
}