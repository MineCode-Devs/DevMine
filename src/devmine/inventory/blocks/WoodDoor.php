<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;

class WoodDoor extends Door{

	protected $id = self::WOOD_DOOR_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Wood Door Block";
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function getHardness() {
		return 3;
	}

	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	public function getDrops(Item $item) : array {
		return [
			[Item::WOODEN_DOOR, 0, 1],
		];
	}
}