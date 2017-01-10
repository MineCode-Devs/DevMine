<?php



namespace devmine\inventory\layout;


use devmine\worlds\Position;


class FakeBlockMenu extends Position implements InventoryHolder{

	private $inventory;

	public function __construct(Inventory $inventory, Position $pos){
		$this->inventory = $inventory;
		parent::__construct($pos->x, $pos->y, $pos->z, $pos->level);
	}

	public function getInventory(){
		return $this->inventory;
	}
}