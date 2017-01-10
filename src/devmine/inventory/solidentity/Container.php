<?php



namespace devmine\inventory\solidentity;

use devmine\inventory\items\Item;
use devmine\server\network;

interface Container{

	/**
	 * @param int $index
	 *
	 * @return Item
	 */
	public function getItem($index);

	/**
	 * @param int  $index
	 * @param Item $item
	 */
	public function setItem($index, Item $item);

	/**
	 * @return int
	 */
	public function getSize();
}
