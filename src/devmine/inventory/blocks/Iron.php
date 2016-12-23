<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;

class Iron extends Solid{

	protected $id = self::IRON_BLOCK;

	public function __construct(){

	}

	public function getName() : string{
		return "Iron Block";
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getHardness() {
		return 5;
	}

	public function getDrops(Item $item) : array {
		if($item->isPickaxe() >= 3){
			return [
				[Item::IRON_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}
}