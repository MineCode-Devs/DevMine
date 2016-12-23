<?php



namespace devmine\inventory\layout;


use devmine\inventory\items\Item;
use devmine\inventory\solidentity\Furnace;

class FurnaceInventory extends ContainerInventory{
	public function __construct(Furnace $solidentity){
		parent::__construct($solidentity, InventoryType::get(InventoryType::FURNACE));
	}

	/**
	 * @return Furnace
	 */
	public function getHolder(){
		return $this->holder;
	}

	/**
	 * @return Item
	 */
	public function getResult(){
		return $this->getItem(2);
	}

	/**
	 * @return Item
	 */
	public function getFuel(){
		return $this->getItem(1);
	}

	/**
	 * @return Item
	 */
	public function getSmelting(){
		return $this->getItem(0);
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function setResult(Item $item){
		return $this->setItem(2, $item);
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function setFuel(Item $item){
		return $this->setItem(1, $item);
	}

	/**
	 * @param Item $item
	 *
	 * @return bool
	 */
	public function setSmelting(Item $item){
		return $this->setItem(0, $item);
	}

	public function onSlotChange($index, $before){
		parent::onSlotChange($index, $before);

		$this->getHolder()->scheduleUpdate();
	}
}
