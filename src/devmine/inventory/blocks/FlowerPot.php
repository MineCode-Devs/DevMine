<?php
/*
 * Copied from ImagicalMine
 * THIS IS COPIED FROM THE PLUGIN FlowerPot MADE BY @beito123!!
 * https://github.com/beito123/devmine-MP-Plugins/blob/master/test%2FFlowerPot%2Fsrc%2Fbeito%2FFlowerPot%2Fomake%2FSkull.php
 *
 * devmine Project
 */

namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\levels\Level;
use devmine\server\calculations\Vector3;
use devmine\Player;
use devmine\inventory\solidentity\solidentity;
use devmine\server\calculations\AxisAlignedBB;
use devmine\creatures\player\tag\StringTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\ShortTag;
use devmine\creatures\player\tag\CompoundTag;
use devmine\inventory\solidentity\FlowerPot as FlowerPotsolidentity;

class FlowerPot extends Flowable{
	protected $id = Block::FLOWER_POT_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function getName() : string{
		return "Flower Pot Block";
	}

	public function getBoundingBox(){
		return new AxisAlignedBB(
			$this->x + 0.3125,
			$this->y,
			$this->z + 0.3125,
			$this->x + 0.6875,
			$this->y + 0.375,
			$this->z + 0.6875
		);
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($this->getSide(Vector3::SIDE_DOWN)->isTransparent() === false){
			$this->getLevel()->setBlock($block, $this, true, true);
			$nbt = new CompoundTag("", [
				new StringTag("id", solidentity::FLOWER_POT),
				new IntTag("x", $block->x),
				new IntTag("y", $block->y),
				new IntTag("z", $block->z),
				new ShortTag("item", 0),
				new IntTag("data", 0),
			]);
			
			if($item->hasCustomBlockData()){
			    foreach($item->getCustomBlockData() as $key => $v){
				    $nbt->{$key} = $v;
			    }
		    }
		    
			$pot = solidentity::createsolidentity("FlowerPot", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			return true;
		}
		return false;
	}

	public function onActivate(Item $item, Player $player = null){
		$solidentity = $this->getLevel()->getsolidentity($this);
		if($solidentity instanceof FlowerPotsolidentity){
			if($solidentity->getFlowerPotItem() === Item::AIR){
				switch($item->getId()){
					case Item::TALL_GRASS:
						if($item->getDamage() === 1){
							break;
						}
					case Item::SAPLING:
					case Item::DEAD_BUSH:
					case Item::DANDELION:
					case Item::RED_FLOWER:
					case Item::BROWN_MUSHROOM:
					case Item::RED_MUSHROOM:
					case Item::CACTUS:
						$solidentity->setFlowerPotData($item->getId(), $item->getDamage());
						$this->setDamage($item->getId());
						$this->getLevel()->setBlock($this, $this, true, false);
						if($player->isSurvival()){
							$item->setCount($item->getCount() - 1);
							$player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
						}
						return true;
						break;
				}
			}
		}
		return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(Vector3::SIDE_DOWN)->isTransparent()){
				$this->getLevel()->useBreakOn($this);
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		return false;
	}

	public function getDrops(Item $item) : array {
		$items = array([Item::FLOWER_POT, 0, 1]);
		/** @var FlowerPotsolidentity $solidentity */
		if($this->getLevel()!=null && (($solidentity = $this->getLevel()->getsolidentity($this)) instanceof FlowerPotsolidentity)){
			if($solidentity->getFlowerPotItem() !== Item::AIR){
				$items[] = array($solidentity->getFlowerPotItem(), $solidentity->getFlowerPotData(), 1);
			}
		}
		return $items;
	}
}
