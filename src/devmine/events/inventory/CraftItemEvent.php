<?php
/**
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
namespace devmine\events\inventory;

use devmine\events\Cancellable;
use devmine\events\Event;
use devmine\inventory\layout\Recipe;
use devmine\inventory\items\Item;
use devmine\creatures\player;

class CraftItemEvent extends Event implements Cancellable{
	public static $handlerList = null;
	/** @var Item[] */
	private $input = [];
	/** @var Recipe */
	private $recipe;
	/** @var \devmine\creatures\player */
	private $player;

	/**
	 * @param \devmine\creatures\player $player
	 * @param Item[]             $input
	 * @param Recipe             $recipe
	 */
	public function __construct(Player $player, array $input, Recipe $recipe){
		$this->player = $player;
		$this->input = $input;
		$this->recipe = $recipe;
	}

	/**
	 * @return Item[]
	 */
	public function getInput(){
		$items = [];
		foreach($this->input as $i => $item){
			$items[$i] = clone $item;
		}
		return $items;
	}

	/**
	 * @return Recipe
	 */
	public function getRecipe(){
		return $this->recipe;
	}

	/**
	 * @return \devmine\creatures\player
	 */
	public function getPlayer(){
		return $this->player;
	}
}
