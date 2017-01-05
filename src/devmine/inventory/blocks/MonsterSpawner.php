<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____ 
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author devmine Team
 * @link http://www.devmine.net/
 *
 *
*/

namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\StringTag;
use devmine\inventory\solidentity\solidentity;
use devmine\inventory\solidentity\MobSpawner;
use devmine\Player;

class MonsterSpawner extends Solid{

	protected $id = self::MONSTER_SPAWNER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness() {
		return 5;
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function getName() : string{
		return "Monster Spawner";
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function onActivate(Item $item, Player $player = null){
		if($this->getDamage() == 0){
			if($item->getId() == Item::SPAWN_EGG){
				$solidentity = $this->getLevel()->getsolidentity($this);
				if($solidentity instanceof MobSpawner){
					$this->meta = $item->getDamage();
					//$this->getLevel()->setBlock($this, $this, true, false);
					$solidentity->setEntityId($this->meta);
				}
				return true;
			}
		}
		return false;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){

		$this->getLevel()->setBlock($block, $this, true, true);
		$nbt = new CompoundTag("", [
			new StringTag("id", solidentity::MOB_SPAWNER),
			new IntTag("x", $block->x),
			new IntTag("y", $block->y),
			new IntTag("z", $block->z),
			new IntTag("EntityId", 0),
		]);
		
		if($item->hasCustomBlockData()){
			foreach($item->getCustomBlockData() as $key => $v){
				$nbt->{$key} = $v;
			}
		}
		
		solidentity::createsolidentity(solidentity::MOB_SPAWNER, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
		return true;
	}

	public function getDrops(Item $item) : array {
		return [];
	}
}