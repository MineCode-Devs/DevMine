<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\inventory\items\enchantment\enchantment;
use devmine\levels\Level;

class RedstoneOre extends Solid{

	protected $id = self::REDSTONE_ORE;

	public function __construct(){

	}

	public function getName() : string{
		return "Redstone Ore";
	}

	public function getHardness() {
		return 3;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL or $type === Level::BLOCK_UPDATE_TOUCH){
			$this->getLevel()->setBlock($this, Block::get(Item::GLOWING_REDSTONE_ORE, $this->meta));

			return Level::BLOCK_UPDATE_WEAK;
		}

		return false;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getDrops(Item $item) : array {
		if($item->isPickaxe() >= Tool::TIER_IRON){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return [
					[Item::REDSTONE_ORE, 0, 1],
				];
			}else{
				$fortunel = $item->getEnchantmentLevel(Enchantment::TYPE_MINING_FORTUNE);
				$fortunel = $fortunel > 3 ? 3 : $fortunel;
				return [
					[Item::REDSTONE_DUST, 0, mt_rand(4, 5 + $fortunel)],
				];
			}
		}else{
			return [];
		}
	}
}
