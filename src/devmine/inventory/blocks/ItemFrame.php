<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\FloatTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\ByteTag;
use devmine\creatures\player\tag\StringTag;
use devmine\inventory\solidentity\solidentity;
use devmine\inventory\solidentity\ItemFrame as ItemFramesolidentity;
use devmine\Player;

class ItemFrame extends Transparent{
	protected $id = self::ITEM_FRAME_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Item Frame";
	}

	public function canBeActivated() : bool{
		return true;
	}

	public function onActivate(Item $item, Player $player = null){
		$solidentity = $this->getLevel()->getsolidentity($this);
		if(!$solidentity instanceof ItemFramesolidentity){
			$nbt = new CompoundTag("", [
				new StringTag("id", solidentity::ITEM_FRAME),
				new IntTag("x", $this->x),
				new IntTag("y", $this->y),
				new IntTag("z", $this->z),
				new ByteTag("ItemRotation", 0),
				new FloatTag("ItemDropChance", 1.0)
			]);
			solidentity::createsolidentity(solidentity::ITEM_FRAME, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
		}

		if($solidentity->getItem()->getId() === 0){
			$solidentity->setItem(Item::get($item->getId(), $item->getDamage(), 1));
			if($player instanceof Player){
				if($player->isSurvival()) {
					$count = $item->getCount();
					if(--$count <= 0){
						$player->getInventory()->setItemInHand(Item::get(Item::AIR));
						return true;
					}

					$item->setCount($count);
					$player->getInventory()->setItemInHand($item);
				}
			}
		}else{
			$itemRot = $solidentity->getItemRotation();
			if($itemRot === 7) $itemRot = 0;
			else $itemRot++;
			$solidentity->setItemRotation($itemRot);
		}

		return true;
	}

	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air(), true, false);
	}

	public function getDrops(Item $item) : array{
		if($this->getLevel()==null){
			return [];
		}
		$solidentity = $this->getLevel()->getsolidentity($this);
		if(!$solidentity instanceof ItemFramesolidentity){
			return [
				[Item::ITEM_FRAME, 0, 1]
			];
		}
		$chance = mt_rand(0, 100);
		if($chance <= ($solidentity->getItemDropChance() * 100)){
			return [
				[Item::ITEM_FRAME, 0 ,1],
				[$solidentity->getItem()->getId(), $solidentity->getItem()->getDamage(), 1]
			];
		}
		return [
			[Item::ITEM_FRAME, 0 ,1]
		];
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($target->isTransparent() === false and $face > 1 and $block->isSolid() === false){
			$faces = [
				2 => 3,
				3 => 2,
				4 => 1,
				5 => 0,
			];
			$this->meta = $faces[$face];
			$this->getLevel()->setBlock($block, $this, true, true);
			$nbt = new CompoundTag("", [
				new StringTag("id", solidentity::ITEM_FRAME),
				new IntTag("x", $block->x),
				new IntTag("y", $block->y),
				new IntTag("z", $block->z),
				new ByteTag("ItemRotation", 0),
				new FloatTag("ItemDropChance", 1.0)
			]);
			
			if($item->hasCustomBlockData()){
			    foreach($item->getCustomBlockData() as $key => $v){
				    $nbt->{$key} = $v;
			    }
		    }
		    
			solidentity::createsolidentity(solidentity::ITEM_FRAME, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			return true;
		}
		return false;
	}
}