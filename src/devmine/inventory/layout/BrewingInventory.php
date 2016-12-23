<?php



namespace devmine\inventory\layout;

use devmine\inventory\items\Item;
use devmine\inventory\solidentity\BrewingStand;

class BrewingInventory extends ContainerInventory{
	public function __construct(BrewingStand $solidentity){
		parent::__construct($solidentity, InventoryType::get(InventoryType::BREWING_STAND));
	}

	/**
	 * @return BrewingStand
	 */
	public function getHolder(){
		return $this->holder;
	}

	public function setIngredient(Item $item){
		$this->setItem(0, $item);
	}

	/**
	 * @return Item
	 */
	public function getIngredient(){
		return $this->getItem(0);
	}

	public function onSlotChange($index, $before){
		parent::onSlotChange($index, $before);

		$this->getHolder()->scheduleUpdate();
		$this->getHolder()->updateSurface();
	}
}