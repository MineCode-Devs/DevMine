<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\Player;
use devmine\levels\sound\ButtonClickSound;

class StoneButton extends WoodenButton{
	protected $id = self::STONE_BUTTON;

	public function getName() : string{
		return "Stone Button";
	}

	public function onActivate(Item $item, Player $player = null){
		if(!$this->isActivated()){
			$this->meta ^= 0x08;
			$this->getLevel()->setBlock($this, $this, true, false);
			$this->getLevel()->addSound(new ButtonClickSound($this));
			$this->activate();
			$this->getLevel()->scheduleUpdate($this, 40);
		}
		return true;
	}
}
