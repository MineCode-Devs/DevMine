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
 * @author devmine Team
 * @link   http://www.devmine.net/
 *
 *
 */
namespace devmine\server\events\inventory;

use devmine\server\events\Cancellable;
use devmine\server\events\Event;
use devmine\inventory\layout\Recipe;
use devmine\inventory\items\Item;
use devmine\Player;

class CraftItemEvent extends Event implements Cancellable{
	public static $handlerList = null;
	/** @var Item[] */
	private $input = [];
	/** @var Recipe */
	private $recipe;
	/** @var \devmine\Player */
	private $player;

	/**
	 * @param \devmine\Player $player
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
		foreach($items as $i => $item){
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
	 * @return \devmine\Player
	 */
	public function getPlayer(){
		return $this->player;
	}
}