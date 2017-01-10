<?php



namespace devmine\inventory\blocks;

use devmine\events\block\BlockSpreadEvent;
use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\inventory\items\enchantment\enchantment;
use devmine\worlds\generator\object\TallGrass as TallGrassObject;
use devmine\worlds\Level;
use devmine\server\calculations\Vector3;
use devmine\creatures\player;
use devmine\server\server;
use devmine\utilities\main\Random;

class Grass extends Solid{

	protected $id = self::GRASS;

	public function __construct(){

	}

	public function canBeActivated() : bool{
		return true;
	}

	public function getName() : string{
		return "Grass";
	}

	public function getHardness(){
		return 0.6;
	}

	public function getToolType(){
		return Tool::TYPE_SHOVEL;
	}

	public function getDrops(Item $item) : array{
		if($item->getEnchantmentLevel(Enchantment::TYPE_MINING_SILK_TOUCH) > 0){
			return [
				[Item::GRASS, 0, 1],
			];
		}else{
			return [
				[Item::DIRT, 0, 1],
			];
		}
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_RANDOM){
			$block = $this->getLevel()->getBlock(new Vector3($this->x, $this->y, $this->z));
			if($block->getSide(1)->getLightLevel() < 4){
				Server::getInstance()->getPluginManager()->callEvent($ev = new BlockSpreadEvent($block, $this, new Dirt()));
			}elseif($block->getSide(1)->getLightLevel() >= 9){
				for($l = 0; $l < 4; ++$l){
					$x = mt_rand($this->x - 1, $this->x + 1);
					$y = mt_rand($this->y - 2, $this->y + 2);
					$z = mt_rand($this->z - 1, $this->z + 1);
					$block = $this->getLevel()->getBlock(new Vector3($x, $y, $z));
					if($block->getId() === Block::DIRT && $block->getDamage() === 0x0F && $block->getSide(1)->getLightLevel() >= 4 && $block->z <= 2){
						Server::getInstance()->getPluginManager()->callEvent($ev = new BlockSpreadEvent($block, $this, new Grass()));
						if(!$ev->isCancelled()){
							$this->getLevel()->setBlock($block, $ev->getNewState());
						}
					}
				}
			}
		}
	}

	public function onActivate(Item $item, Player $player = null){
		if($item->getId() === Item::DYE and $item->getDamage() === 0x0F){
			$item->count--;
			TallGrassObject::growGrass($this->getLevel(), $this, new Random(mt_rand()), 8, 2);

			return true;
		}elseif($item->isHoe()){
			$item->useOn($this);
			$this->getLevel()->setBlock($this, new Farmland());

			return true;
		}elseif($item->isShovel() and $this->getSide(1)->getId() === Block::AIR){
			$item->useOn($this);
			$this->getLevel()->setBlock($this, new GrassPath());

			return true;
		}

		return false;
	}
}
