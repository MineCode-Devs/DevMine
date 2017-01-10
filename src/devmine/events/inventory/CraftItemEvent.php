<?php

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
