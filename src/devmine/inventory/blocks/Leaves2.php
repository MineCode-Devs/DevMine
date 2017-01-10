<?php



namespace devmine\inventory\blocks;

use devmine\events\block\LeavesDecayEvent;
use devmine\inventory\items\Item;
use devmine\inventory\items\enchantment\enchantment;
use devmine\worlds\Level;
use devmine\creatures\player;
use devmine\server\server;

class Leaves2 extends Leaves{

	const WOOD_TYPE = self::WOOD2;

	protected $id = self::LEAVES2;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		static $names = [
			self::ACACIA => "Acacia Leaves",
			self::DARK_OAK => "Dark Oak Leaves",
		];
		return $names[$this->meta & 0x01];
	}

	public function getDrops(Item $item) : array {
		$drops = [];
		if($item->isShears() or $item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
			$drops[] = [$this->id, $this->meta & 0x01, 1];
		}else{
			$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
			$fortunel = min(3, $fortunel);
			$rates = [20,16,12,10];
			if(mt_rand(1, $rates[$fortunel]) === 1){ //Saplings
				$drops[] = [Item::SAPLING, ($this->meta & 0x01) | 0x04, 1];
			}
		}

		return $drops;
	}
}
