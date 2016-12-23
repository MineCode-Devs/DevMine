<?php



namespace devmine\inventory\layout;

use devmine\inventory\solidentity\Dispenser;

class DispenserInventory extends ContainerInventory{
	public function __construct(Dispenser $solidentity){
		parent::__construct($solidentity, InventoryType::get(InventoryType::DISPENSER));
	}

	/**
	 * @return Dispenser
	 */
	public function getHolder(){
		return $this->holder;
	}
}