<?php



namespace devmine\inventory\layout;

use devmine\utilities\main\UUID;

interface Recipe{

	/**
	 * @return \devmine\inventory\items\Item
	 */
	public function getResult();

	public function registerToCraftingManager();

	/**
	 * @return UUID
	 */
	public function getId();
}