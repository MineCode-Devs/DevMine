<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Tool;
use devmine\inventory\items\Item;
use devmine\inventory\items\enchantment\enchantment;

class Podzol extends Solid{

	protected $id = self::PODZOL;

	public function __construct(){

	}

	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	public function getName() : string{
		return "Podzol";
	}

	public function getHardness() {
		return 0.5;
	}

	public function getResistance(){
		return 2.5;
	}

	public function getDrops(Item $item) : array{
		if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
			return [
				[Item::PODZOL, 0, 1],
			];
		}else{
			return [
				[Item::DIRT, 0, 1],
			];
		}
		
	}
}
