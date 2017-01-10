<?php



namespace devmine\events\inventory;

use devmine\events\Cancellable;
use devmine\inventory\layout\Inventory;
use devmine\creatures\player;

class InventoryOpenEvent extends InventoryEvent implements Cancellable{
	public static $handlerList = null;

	/** @var Player */
	private $who;

	/**
	 * @param Inventory $inventory
	 * @param Player    $who
	 */
	public function __construct(Inventory $inventory, Player $who){
		$this->who = $who;
		parent::__construct($inventory);
	}

	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->who;
	}

}