<?php



namespace devmine\events\inventory;

use devmine\creatures\entities\Item;
use devmine\events\Cancellable;
use devmine\inventory\layout\Inventory;

class InventoryPickupItemEvent extends InventoryEvent implements Cancellable{
	public static $handlerList = null;

	/** @var Item */
	private $item;

	/**
	 * @param Inventory $inventory
	 * @param Item      $item
	 */
	public function __construct(Inventory $inventory, Item $item){
		$this->item = $item;
		parent::__construct($inventory);
	}

	/**
	 * @return Item
	 */
	public function getItem(){
		return $this->item;
	}

}