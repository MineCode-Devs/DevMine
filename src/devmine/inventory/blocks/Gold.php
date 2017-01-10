<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;

class Gold extends Solid{

	protected $id = self::GOLD_BLOCK;

	public function __construct(){

	}

	public function getName() : string{
		return "Gold Block";
	}

	public function getHardness() {
		return 3;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getDrops(Item $item) : array {
		if($item->isPickaxe() >= 4){
			return [
				[Item::GOLD_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}
}