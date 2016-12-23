<?php



namespace devmine\inventory\blocks;

use devmine\server\events\player\PlayerBucketEmptyEvent;
use devmine\server\events\player\PlayerBucketFillEvent;
use devmine\server\events\player\PlayerGlassBottleEvent;
use devmine\inventory\items\Armor;
use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\inventory\items\Potion;
use devmine\levels\sound\ExplodeSound;
use devmine\levels\sound\GraySplashSound;
use devmine\levels\sound\SpellSound;
use devmine\levels\sound\SplashSound;
use devmine\Player;
use devmine\Server;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\ByteTag;
use devmine\creatures\player\tag\ListTag;
use devmine\creatures\player\tag\StringTag;
use devmine\creatures\player\tag\ShortTag;
use devmine\creatures\player\tag\IntTag;
use devmine\inventory\solidentity\Cauldron as solidentityCauldron;
use devmine\inventory\solidentity\solidentity;
use devmine\utilities\main\Color;

class Cauldron extends Solid{

	protected $id = self::CAULDRON_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 2;
	}

	public function getName() : string{
		return "Cauldron";
	}

	public function canBeActivated() : bool{
		return true;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$nbt = new CompoundTag("", [
			new StringTag("id", solidentity::CAULDRON),
			new IntTag("x", $block->x),
			new IntTag("y", $block->y),
			new IntTag("z", $block->z),
			new ShortTag("PotionId", 0xffff),
			new ByteTag("SplashPotion", 0),
			new ListTag("Items", [])
		]);
		
		if($item->hasCustomBlockData()){
			foreach($item->getCustomBlockData() as $key => $v){
				$nbt->{$key} = $v;
			}
		}
		
		$chunk = $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4);
		$solidentity = solidentity::createsolidentity("Cauldron", $chunk, $nbt);//
		$this->getLevel()->setBlock($block, $this, true, true);
		return true;
	}

	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air(), true);
		return true;
	}

	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 1){
			return [
				[Item::CAULDRON, 0, 1]
			];
		}
		return [];
	}

	public function update(){//umm... right update method...?
		$this->getLevel()->setBlock($this, Block::get($this->id, $this->meta + 1), true);
		$this->getLevel()->setBlock($this, $this, true);//Undo the damage value
	}

	public function isEmpty(){
		return $this->meta === 0x00;
	}

	public function isFull(){
		return $this->meta === 0x06;
	}

	public function onActivate(Item $item, Player $player = null){//@author iTX. rewrite @Dog194
		$solidentity = $this->getLevel()->getsolidentity($this);
		if(!($solidentity instanceof solidentityCauldron)){
			return false;
		}
		switch($item->getId()){
			case Item::BUCKET:
				if($item->getDamage() === 0){//empty bucket
					if(!$this->isFull() or $solidentity->isCustomColor() or $solidentity->hasPotion()){
						break;
					}
					$bucket = clone $item;
					$bucket->setDamage(8);//water bucket
					Server::getInstance()->getPluginManager()->callEvent($ev = new PlayerBucketFillEvent($player, $this, 0, $item, $bucket));
					if(!$ev->isCancelled()){
						if($player->isSurvival()){
							$player->getInventory()->setItemInHand($ev->getItem());
						}
						$this->meta = 0;//empty
						$this->getLevel()->setBlock($this, $this, true);
						$solidentity->clearCustomColor();
						$this->getLevel()->addSound(new SplashSound($this->add(0.5, 1, 0.5)));
					}
				}elseif($item->getDamage() === 8){//water bucket
					if($this->isFull() and !$solidentity->isCustomColor() and !$solidentity->hasPotion()){
						break;
					}
					$bucket = clone $item;
					$bucket->setDamage(0);//empty bucket
					Server::getInstance()->getPluginManager()->callEvent($ev = new PlayerBucketEmptyEvent($player, $this, 0, $item, $bucket));
					if(!$ev->isCancelled()){
						if($player->isSurvival()){
							$player->getInventory()->setItemInHand($ev->getItem());
						}
						if($solidentity->hasPotion()){//if has potion
							$this->meta = 0;//empty
							$solidentity->setPotionId(0xffff);//reset potion
							$solidentity->setSplashPotion(false);
							$solidentity->clearCustomColor();
							$this->getLevel()->setBlock($this, $this, true);
							$this->getLevel()->addSound(new ExplodeSound($this->add(0.5, 0, 0.5)));
						}else{
							$this->meta = 6;//fill
							$solidentity->clearCustomColor();
							$this->getLevel()->setBlock($this, $this, true);
							$this->getLevel()->addSound(new SplashSound($this->add(0.5, 1, 0.5)));
						}
						$this->update();
					}
				}
				break;
			case Item::DYE:
				if($solidentity->hasPotion()) break;
				$color = Color::getDyeColor($item->getDamage());
				if($solidentity->isCustomColor()){
					$color = Color::averageColor($color, $solidentity->getCustomColor());
				}
				if($player->isSurvival()){
					$item->setCount($item->getCount() - 1);
					/*if($item->getCount() <= 0){
						$player->getInventory()->setItemInHand(Item::get(Item::AIR));
					}*/
				}
				$solidentity->setCustomColor($color);
				$this->getLevel()->addSound(new SplashSound($this->add(0.5, 1, 0.5)));
				$this->update();
				break;
			case Item::LEATHER_CAP:
			case Item::LEATHER_TUNIC:
			case Item::LEATHER_PANTS:
			case Item::LEATHER_BOOTS:
				if($this->isEmpty()) break;
				if($solidentity->isCustomColor()){
					--$this->meta;
					$this->getLevel()->setBlock($this, $this, true);
					$newItem = clone $item;
					/** @var Armor $newItem */
					$newItem->setCustomColor($solidentity->getCustomColor());
					$player->getInventory()->setItemInHand($newItem);
					$this->getLevel()->addSound(new SplashSound($this->add(0.5, 1, 0.5)));
					if($this->isEmpty()){
						$solidentity->clearCustomColor();
					}
				}else{
					--$this->meta;
					$this->getLevel()->setBlock($this, $this, true);
					$newItem = clone $item;
					/** @var Armor $newItem */
					$newItem->clearCustomColor();
					$player->getInventory()->setItemInHand($newItem);
					$this->getLevel()->addSound(new SplashSound($this->add(0.5, 1, 0.5)));
				}
				break;
			case Item::POTION:
			case Item::SPLASH_POTION:
				if(!$this->isEmpty() and (($solidentity->getPotionId() !== $item->getDamage() and $item->getDamage() !== Potion::WATER_BOTTLE) or
						($item->getId() === Item::POTION and $solidentity->getSplashPotion()) or
						($item->getId() === Item::SPLASH_POTION and !$solidentity->getSplashPotion()) and $item->getDamage() !== 0 or
						($item->getDamage() === Potion::WATER_BOTTLE and $solidentity->hasPotion()))
				){//long...
					$this->meta = 0x00;
					$this->getLevel()->setBlock($this, $this, true);
					$solidentity->setPotionId(0xffff);//reset
					$solidentity->setSplashPotion(false);
					$solidentity->clearCustomColor();
					if($player->isSurvival()){
						$player->getInventory()->setItemInHand(Item::get(Item::GLASS_BOTTLE));
					}
					$this->getLevel()->addSound(new ExplodeSound($this->add(0.5, 0, 0.5)));
				}elseif($item->getDamage() === Potion::WATER_BOTTLE){//水瓶 喷溅型水瓶
					$this->meta += 2;
					if($this->meta > 0x06) $this->meta = 0x06;
					$this->getLevel()->setBlock($this, $this, true);
					if($player->isSurvival()){
						$player->getInventory()->setItemInHand(Item::get(Item::GLASS_BOTTLE));
					}
					$solidentity->setPotionId(0xffff);
					$solidentity->setSplashPotion(false);
					$solidentity->clearCustomColor();
					$this->getLevel()->addSound(new SplashSound($this->add(0.5, 1, 0.5)));
				}elseif(!$this->isFull()){
					$this->meta += 2;
					if($this->meta > 0x06) $this->meta = 0x06;
					$solidentity->setPotionId($item->getDamage());
					$solidentity->setSplashPotion($item->getId() === Item::SPLASH_POTION);
					$solidentity->clearCustomColor();
					$this->getLevel()->setBlock($this, $this, true);
					if($player->isSurvival()){
						$player->getInventory()->setItemInHand(Item::get(Item::GLASS_BOTTLE));
					}
					$color = Potion::getColor($item->getDamage());
					$this->getLevel()->addSound(new SpellSound($this->add(0.5, 1, 0.5), $color[0], $color[1], $color[2]));
				}
				break;
			case Item::GLASS_BOTTLE:
				$player->getServer()->getPluginManager()->callEvent($ev = new PlayerGlassBottleEvent($player, $this, $item));
				if($ev->isCancelled()){
					return false;
				}
				if($this->meta < 2) {
					break;
				}
				if($solidentity->hasPotion()){
					$this->meta -= 2;
					if($solidentity->getSplashPotion() === true){
						$result = Item::get(Item::SPLASH_POTION, $solidentity->getPotionId());
					}else{
						$result = Item::get(Item::POTION, $solidentity->getPotionId());
					}
					if($this->isEmpty()){
						$solidentity->setPotionId(0xffff);//reset
						$solidentity->setSplashPotion(false);
						$solidentity->clearCustomColor();
					}
					$this->getLevel()->setBlock($this, $this, true);
					$this->addItem($item, $player, $result);
					$color = Potion::getColor($result->getDamage());
					$this->getLevel()->addSound(new SpellSound($this->add(0.5, 1, 0.5), $color[0], $color[1], $color[2]));
				}else{
					$this->meta -= 2;
					$this->getLevel()->setBlock($this, $this, true);
					if($player->isSurvival()){
						$result = Item::get(Item::POTION, Potion::WATER_BOTTLE);
						$this->addItem($item, $player, $result);
					}
					$this->getLevel()->addSound(new GraySplashSound($this->add(0.5, 1, 0.5)));
				}
				break;
		}
		return true;
	}

	public function addItem(Item $item, Player $player, Item $result){
		if($item->getCount() <= 1){
			$player->getInventory()->setItemInHand($result);
		}else{
			$item->setCount($item->getCount() - 1);
			if($player->getInventory()->canAddItem($result) === true){
				$player->getInventory()->addItem($result);
			}else{
				$motion = $player->getDirectionVector()->multiply(0.4);
				$position = clone $player->getPosition();
				$player->getLevel()->dropItem($position->add(0 , 0.5, 0), $result , $motion, 40);
			}
		}
	}
}