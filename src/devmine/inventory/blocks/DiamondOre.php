<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\inventory\items\enchantment\enchantment;

class DiamondOre extends Solid{

	protected $id = self::DIAMOND_ORE;

	public function __construct(){

	}

	public function getHardness() {
		return 3;
	}

	public function getName() : string{
		return "Diamond Ore";
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getDrops(Item $item) : array {
		if($item->isPickaxe() >= 4){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return [
					[Item::DIAMOND_ORE, 0, 1],
				];
			}else{
				$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
				$fortunel = $fortunel > 3 ? 3 : $fortunel;
				$times = [1,1,2,3,4];
				$time = $times[mt_rand(0, $fortunel + 1)];
				return [
					[Item::DIAMOND, 0, $time],
				];
			}
			
		}else{
			return [];
		}
	}
}
