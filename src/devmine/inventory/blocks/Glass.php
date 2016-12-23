<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\enchantment\enchantment;

class Glass extends Transparent{

	protected $id = self::GLASS;

	public function __construct(){

	}

	public function getName() : string{
		return "Glass";
	}

	public function getHardness() {
		return 0.3;
	}

	public function getDrops(Item $item) : array {
		if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
			return [
				[Item::GLASS, 0, 1],
			];
		}else{
			return [];
		}
	}
}
