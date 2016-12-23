<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;

class Emerald extends Solid{

	protected $id = self::EMERALD_BLOCK;

	public function __construct(){

	}

	public function getHardness() {
		return 5;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getName() : string{
		return "Emerald Block";
	}

	public function getDrops(Item $item) : array {
		if($item->isPickaxe() >= 4){
			return [
				[Item::EMERALD_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}
}