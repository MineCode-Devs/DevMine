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
 * @author Mostly by PocketMine team, modified by DevMine Team
 * @link http://www.pocketmine.net/
 * 
 *
*/

namespace devmine\inventory\blocks;

use devmine\events\TranslationContainter;
use devmine\inventory\items\Item;
use devmine\worlds\Explosion;
use devmine\worlds\Level;
use devmine\server\calculations\AxisAlignedBB;
use devmine\creatures\player;
use devmine\utilities\main\TextFormat;

class Bed extends Transparent{

	protected $id = self::BED_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function getHardness() {
		return 0.2;
	}

	public function getName() : string{
		return "Bed Block";
	}

	protected function recalculateBoundingBox() {
		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 0.5625,
			$this->z + 1
		);
	}

	public function onActivate(Item $item, Player $player = null){
		if($this->getLevel()->getDimension() == Level::DIMENSION_NETHER){
			$explosion = new Explosion($this, 6, $this);
			$explosion->explode();
			return true;
		}

		$time = $this->getLevel()->getTime() % Level::TIME_FULL;

		$isNight = ($time >= Level::TIME_NIGHT and $time < Level::TIME_SUNRISE);

		if($player instanceof Player and !$isNight){
			$player->sendMessage(new TranslationContainter(TextFormat::GRAY . "%tile.bed.noSleep"));
			return true;
		}

		$blockNorth = $this->getSide(2); //Gets the blocks around them
		$blockSouth = $this->getSide(3);
		$blockEast = $this->getSide(5);
		$blockWest = $this->getSide(4);
		if(($this->meta & 0x08) === 0x08){ //This is the Top part of bed
			$b = $this;
		}else{ //Bottom Part of Bed
			if($blockNorth->getId() === $this->id and ($blockNorth->meta & 0x08) === 0x08){
				$b = $blockNorth;
			}elseif($blockSouth->getId() === $this->id and ($blockSouth->meta & 0x08) === 0x08){
				$b = $blockSouth;
			}elseif($blockEast->getId() === $this->id and ($blockEast->meta & 0x08) === 0x08){
				$b = $blockEast;
			}elseif($blockWest->getId() === $this->id and ($blockWest->meta & 0x08) === 0x08){
				$b = $blockWest;
			}else{
				if($player instanceof Player){
					$player->sendMessage(TextFormat::GRAY . "This bed is incomplete");
				}

				return true;
			}
		}

		if($player instanceof Player and $player->sleepOn($b) === false){
			$player->sendMessage(new TranslationContainter(TextFormat::GRAY . "%tile.bed.occupied"));
		}

		return true;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->isTransparent() === false){
			$faces = [
				0 => 3,
				1 => 4,
				2 => 2,
				3 => 5,
			];
			$d = $player instanceof Player ? $player->getDirection() : 0;
			$next = $this->getSide($faces[(($d + 3) % 4)]);
			$downNext = $this->getSide(0);
			if($next->canBeReplaced() === true and $downNext->isTransparent() === false){
				$meta = (($d + 3) % 4) & 0x03;
				$this->getLevel()->setBlock($block, Block::get($this->id, $meta), true, true);
				$this->getLevel()->setBlock($next, Block::get($this->id, $meta | 0x08), true, true);

				return true;
			}
		}

		return false;
	}

	public function onBreak(Item $item){
		$sides = [
			0 => 3,
			1 => 4,
			2 => 2,
			3 => 5,
			8 => 2,
			9 => 5,
			10 => 3,
			11 => 4,
		];

		if(($this->meta & 0x08) === 0x08){ //This is the Top part of bed
			$next = $this->getSide($sides[$this->meta]);
			if($next->getId() === $this->id and ($next->meta | 0x08) === $this->meta){ //Checks if the block ID and meta are right
				$this->getLevel()->setBlock($next, new Air(), true, true);
			}
		}else{ //Bottom Part of Bed
			$next = $this->getSide($sides[$this->meta]);
			if($next->getId() === $this->id and $next->meta === ($this->meta | 0x08)){
				$this->getLevel()->setBlock($next, new Air(), true, true);
			}
		}
		$this->getLevel()->setBlock($this, new Air(), true, true);

		return true;
	}

	public function getDrops(Item $item) : array {
		return [
			[Item::BED, 0, 1],
		];
	}

}
