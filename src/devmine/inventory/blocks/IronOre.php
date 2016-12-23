<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;

class IronOre extends Solid{

	protected $id = self::IRON_ORE;

	public function __construct(){

	}

	public function getName() : string{
		return "Iron Ore";
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getHardness() {
		return 3;
	}

	public function getDrops(Item $item) : array {
		if($item->isPickaxe() >= 3){
			return [
				[Item::IRON_ORE, 0, 1],
			];
		}else{
			return [];
		}
	}
}