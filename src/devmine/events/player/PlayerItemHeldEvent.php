<?php



namespace devmine\events\player;

use devmine\events;
use devmine\events\Cancellable;
use devmine\inventory\items\Item;
use devmine\creatures\player;

class PlayerItemHeldEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	private $item;
	private $slot;
	private $inventorySlot;

	public function __construct(Player $player, Item $item, $inventorySlot, $slot){
		$this->player = $player;
		$this->item = $item;
		$this->inventorySlot = (int) $inventorySlot;
		$this->slot = (int) $slot;
	}

	public function getSlot(){
		return $this->slot;
	}

	public function getInventorySlot(){
		return $this->inventorySlot;
	}

	public function getItem(){
		return $this->item;
	}

}