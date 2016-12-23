<?php



namespace devmine\inventory\blocks;

use devmine\creatures\entities\Entity;
use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\inventory\items\enchantment\enchantment;

class Cobweb extends Flowable{

	protected $id = self::COBWEB;

	public function __construct(){

	}

	public function hasEntityCollision(){
		return true;
	}

	public function getName() : string{
		return "Cobweb";
	}

	public function getHardness() {
		return 4;
	}

	public function getToolType(){
		return Tool::TYPE_SHEARS;
	}

	public function onEntityCollide(Entity $entity){
		$entity->resetFallDistance();
	}

	public function getDrops(Item $item) : array {
		if($item->isShears()){
			return [
				[Item::COBWEB, 0, 1],
			];
		}elseif($item->isSword() >= Tool::TIER_WOODEN){
			if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
				return [
					[Item::COBWEB, 0, 1],
				];
			}else{
				return [
					[Item::STRING, 0, 1],
				];
			}
		}
		return [];
	}
}
