<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;

class Lapis extends Solid{

	protected $id = self::LAPIS_BLOCK;

	public function __construct(){

	}

	public function getName() : string{
		return "Lapis Lazuli Block";
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
				[Item::LAPIS_BLOCK, 0, 1],
			];
		}else{
			return [];
		}
	}

}