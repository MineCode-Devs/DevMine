<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\creatures\player;

//TODO: check orientation
class Workbench extends Solid{

	protected $id = self::WORKBENCH;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function getHardness() {
		return 2.5;
	}

	public function getName() : string{
		return "Crafting Table";
	}

	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	public function onActivate(Item $item, Player $player = null){
		if($player instanceof Player){
			if($player->getServer()->limitedCreative and $player->isCreative()) return true;
			$player->craftingType = Player::CRAFTING_BIG;
		}

		return true;
	}

	public function getDrops(Item $item) : array {
		return [
			[$this->id, 0, 1],
		];
	}
}