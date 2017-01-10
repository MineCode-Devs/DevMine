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
 * @link   http://www.pocketmine.net/
 *
 *
 */

namespace devmine\events\block;

use devmine\inventory\blocks\Block;
use devmine\events\Cancellable;
use devmine\inventory\items\Item;
use devmine\creatures\player;

/**
 * Called when a player places a block
 */
class BlockPlaceEvent extends BlockEvent implements Cancellable{
	public static $handlerList = null;

	/** @var \devmine\creatures\player */
	protected $player;

	/** @var \devmine\inventory\items\Item */
	protected $item;


	protected $blockReplace;
	protected $blockAgainst;

	public function __construct(Player $player, Block $blockPlace, Block $blockReplace, Block $blockAgainst, Item $item){
		$this->block = $blockPlace;
		$this->blockReplace = $blockReplace;
		$this->blockAgainst = $blockAgainst;
		$this->item = $item;
		$this->player = $player;
	}

	public function getPlayer(){
		return $this->player;
	}

	/**
	 * Gets the item in hand
	 *
	 * @return mixed
	 */
	public function getItem(){
		return $this->item;
	}

	public function getBlockReplaced(){
		return $this->blockReplace;
	}

	public function getBlockAgainst(){
		return $this->blockAgainst;
	}
}