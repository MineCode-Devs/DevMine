<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\inventory\items\enchantment\enchantment;

class Melon extends Transparent{

	protected $id = self::MELON_BLOCK;

	public function __construct(){

	}

	public function getName() : string{
		return "Melon Block";
	}

	public function getHardness() {
		return 1;
	}

	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	public function getDrops(Item $item) : array {
		if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
			return [
				[Item::MELON_BLOCK, 0, 1],
			];
		}else{
			$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
			$fortunel = $fortunel > 2 ? 2 : $fortunel; //Note: for Melon level 2 is the same 3 So highest is 2
			return [
				[Item::MELON_SLICE, 0, mt_rand(3, 7 + $fortunel)],
			];
		}
	}
}
