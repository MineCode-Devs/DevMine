<?php

namespace devmine\inventory\items;

use devmine\levels\Level;
use devmine\inventory\blocks\Block;
use devmine\Player;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\ListTag;
use devmine\creatures\player\tag\DoubleTag;
use devmine\creatures\player\tag\FloatTag;
use devmine\creatures\entities\Minecart as MinecartEntity;

class Minecart extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::MINECART, $meta, $count, "Minecart");
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function onActivate(Level $level, Player $player, Block $block, Block $target, $face, $fx, $fy, $fz){
		$minecart = new MinecartEntity($player->getLevel()->getChunk($block->getX() >> 4, $block->getZ() >> 4), new CompoundTag("", [
			"Pos" => new ListTag("Pos", [
				new DoubleTag("", $block->getX()),
				new DoubleTag("", $block->getY() + 0.8),
				new DoubleTag("", $block->getZ())
			]),
			"Motion" => new ListTag("Motion", [
				new DoubleTag("", 0),
				new DoubleTag("", 0),
				new DoubleTag("", 0)
			]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag("", 0),
				new FloatTag("", 0)
			]),
		]));
		$minecart->spawnToAll();

		if($player->isSurvival()){
			$item = $player->getInventory()->getItemInHand();
			$count = $item->getCount();
			if(--$count <= 0){
				$player->getInventory()->setItemInHand(Item::get(Item::AIR));
				return true;
			}

			$item->setCount($count);
			$player->getInventory()->setItemInHand($item);
		}
		
		return true;
	}
}
