<?php



namespace devmine\inventory\layout;

interface InventoryHolder{

	/**
	 * Get the object related inventory
	 *
	 * @return Inventory
	 */
	public function getInventory();
}