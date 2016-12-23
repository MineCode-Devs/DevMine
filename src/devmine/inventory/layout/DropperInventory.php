<?php



namespace devmine\inventory\layout;

use devmine\inventory\solidentity\Dropper;

class DropperInventory extends ContainerInventory{
	public function __construct(Dropper $solidentity){
		parent::__construct($solidentity, InventoryType::get(InventoryType::DROPPER));
	}

	/**
	 * @return Dropper
	 */
	public function getHolder(){
		return $this->holder;
	}
}