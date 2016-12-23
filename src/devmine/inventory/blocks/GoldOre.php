<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;

class GoldOre extends Solid{

	protected $id = self::GOLD_ORE;

	public function __construct(){

	}

	public function getName() : string{
		return "Gold Ore";
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
				[Item::GOLD_ORE, 0, 1],
			];
		}else{
			return [];
		}
	}
}