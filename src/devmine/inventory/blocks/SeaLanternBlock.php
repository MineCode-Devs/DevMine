<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Tool;
use devmine\inventory\items\Item;


class SeaLanternBlock extends Solid{

	protected $id = self::SEA_LANTERN_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getLightLevel(){
		return 15;
	}

	public function getName() : string{
        return "Sea Lantern Block";
	}

	public function getHardness(){
		return 0.3;
	}

	public function getDrops(Item $item) : array {
		return [
			[Item::PRISMARINE_CRYSTALS, 0, 3],
		];
	}

}