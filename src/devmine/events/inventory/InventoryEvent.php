<?php



/**
 * Inventory related events
 */
namespace devmine\events\inventory;

use devmine\events\Event;
use devmine\inventory\layout\Inventory;

abstract class InventoryEvent extends Event{

	/** @var Inventory */
	protected $inventory;

	public function __construct(Inventory $inventory){
		$this->inventory = $inventory;
	}

	/**
	 * @return Inventory
	 */
	public function getInventory(){
		return $this->inventory;
	}

	/**
	 * @return \devmine\creatures\entities\Human[]
	 */
	public function getViewers(){
		return $this->inventory->getViewers();
	}
}