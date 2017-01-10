<?php



namespace devmine\events\inventory;

use devmine\creatures\entities\Arrow;
use devmine\events\Cancellable;
use devmine\inventory\layout\Inventory;

class InventoryPickupArrowEvent extends InventoryEvent implements Cancellable{
	public static $handlerList = null;

	/** @var Arrow */
	private $arrow;

	/**
	 * @param Inventory $inventory
	 * @param Arrow     $arrow
	 */
	public function __construct(Inventory $inventory, Arrow $arrow){
		$this->arrow = $arrow;
		parent::__construct($inventory);
	}

	/**
	 * @return Arrow
	 */
	public function getArrow(){
		return $this->arrow;
	}

}