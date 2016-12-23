<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\inventory\items\enchantment\enchantment;

class Gravel extends Fallable{

	protected $id = self::GRAVEL;

	public function __construct(){

	}

	public function getName() : string{
		return "Gravel";
	}

	public function getHardness() {
		return 0.6;
	}

	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	public function getDrops(Item $item) : array {
		$drops = [];
		if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){//使用精准采集附魔 不掉落燧石
			$drops[] = [Item::GRAVEL, 0, 1];
			return $drops;
		}
		$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
		$fortunel = $fortunel > 3 ? 3 : $fortunel;
		$rates = [10,7,4,1];
		if(mt_rand(1, $rates[$fortunel]) === 1){//10% 14% 25% 100%
			$drops[] = [Item::FLINT, 0, 1];
		}
		if(mt_rand(1, 10) !== 1){//90%
			$drops[] = [Item::GRAVEL, 0, 1];
		}
		return $drops;
	}
}
