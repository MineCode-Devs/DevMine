<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;

class PrismarineBlock extends Solid{

	protected $id = self::PRISMARINE_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		static $names = [
			0 => "Prismarine Block",
			1 => "Dark Prismarine Block",
			2 => "Prismarine Bricks Block",
		];
		return $names[$this->meta & 0x0f];
	}

	public function getHardness(){
		return 1.5;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getDrops(Item $item) : array {
		if($item->isPickaxe() >= Tool::TIER_WOODEN){
			return [
				[$this->id, $this->meta & 0x0f, 1],
			];
		}else{
			return [];
		}
	}
}