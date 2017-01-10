<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\creatures\player;

class Dirt extends Solid{

	protected $id = self::DIRT;

	public function __construct(){

	}

	public function canBeActivated() : bool {
		return true;
	}

	public function getHardness() {
		return 0.5;
	}

	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	public function getName() : string{
		return "Dirt";
	}

	public function onActivate(Item $item, Player $player = null){
		if($item->isHoe()){
			$item->useOn($this, 2);
			$this->getLevel()->setBlock($this, Block::get(Item::FARMLAND, 0), true);

			return true;
		}

		return false;
	}
}