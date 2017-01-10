<?php



namespace devmine\inventory\layout;

use devmine\creatures\player;
use devmine\inventory\items\Item;

interface Transaction{

	//Transaction type constants
	const TYPE_NORMAL = 0;
	const TYPE_DROP_ITEM = 1;

	/**
	 * @return Inventory
	 */
	public function getInventory();

	/**
	 * @return int
	 */
	public function getSlot();

	/**
	 * @return Item
	 */
	public function getTargetItem();

	/**
	 * @return float
	 */
	public function getCreationTime();

	/**
	 * @param Player $source
	 * @return bool
	 */
	public function execute(Player $source): bool;
}