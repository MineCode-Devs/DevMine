<?php



namespace devmine\inventory\blocks;

use devmine\events\block\BlockGrowEvent;
use devmine\inventory\items\Item;
use devmine\worlds\Level;
use devmine\server\calculations\Vector3 as Vector3;
use devmine\creatures\player;
use devmine\server\server;

class Sugarcane extends Flowable{

	protected $id = self::SUGARCANE_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Sugarcane";
	}


	public function getDrops(Item $item) : array {
		return [
			[Item::SUGARCANE, 0, 1],
		];
	}

	public function onActivate(Item $item, Player $player = null){
		if($item->getId() === Item::DYE and $item->getDamage() === 0x0F){ //Bonemeal
			if($this->getSide(0)->getId() !== self::SUGARCANE_BLOCK){
				for($y = 1; $y < 3; ++$y){
					$b = $this->getLevel()->getBlock(new Vector3($this->x, $this->y + $y, $this->z));
					if($b->getId() === self::AIR){
						Server::getInstance()->getPluginManager()->callEvent($ev = new BlockGrowEvent($b, new Sugarcane()));
						if(!$ev->isCancelled()){
							$this->getLevel()->setBlock($b, $ev->getNewState(), true);
						}
						break;
					}
				}
				$this->meta = 0;
				$this->getLevel()->setBlock($this, $this, true);
			}
			if(($player->gamemode & 0x01) === 0){
				$item->count--;
			}

			return true;
		}

		return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$down = $this->getSide(0);
			if($down->isTransparent() === true and $down->getId() !== self::SUGARCANE_BLOCK){
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}elseif($type === Level::BLOCK_UPDATE_RANDOM){
			if($this->getSide(0)->getId() !== self::SUGARCANE_BLOCK){
				if($this->meta === 0x0F){
					for($y = 1; $y < 3; ++$y){
						$b = $this->getLevel()->getBlock(new Vector3($this->x, $this->y + $y, $this->z));
						if($b->getId() === self::AIR){
							$this->getLevel()->setBlock($b, new Sugarcane(), true);
							break;
						}
					}
					$this->meta = 0;
					$this->getLevel()->setBlock($this, $this, true);
				}else{
					++$this->meta;
					$this->getLevel()->setBlock($this, $this, true);
				}

				return Level::BLOCK_UPDATE_RANDOM;
			}
		}

		return false;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() === self::SUGARCANE_BLOCK){
			$this->getLevel()->setBlock($block, new Sugarcane(), true);

			return true;
		}elseif($down->getId() === self::GRASS or $down->getId() === self::DIRT or $down->getId() === self::SAND){
			$block0 = $down->getSide(2);
			$block1 = $down->getSide(3);
			$block2 = $down->getSide(4);
			$block3 = $down->getSide(5);
			if(($block0 instanceof Water) or ($block1 instanceof Water) or ($block2 instanceof Water) or ($block3 instanceof Water)){
				$this->getLevel()->setBlock($block, new Sugarcane(), true);

				return true;
			}
		}

		return false;
	}
}