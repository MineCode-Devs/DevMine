<?php



namespace devmine\inventory\layout;

use devmine\inventory\items\Item;
use devmine\levels\Position;
use devmine\Player;

class AnvilInventory extends ContainerInventory{
	public function __construct(Position $pos){
		parent::__construct(new FakeBlockMenu($this, $pos), InventoryType::get(InventoryType::ANVIL));
	}

	/**
	 * @return FakeBlockMenu
	 */
	public function getHolder(){
		return $this->holder;
	}

	public function onRename(Item $item, Player $player) : bool{
		if($player->getExpLevel() > $item->getRepairCost()){
			$player->setExpLevel($player->getExpLevel() - $item->getRepairCost());
			return true;
		}
		return false;
	}

	public function onClose(Player $who){
		$who->updateExperience();
		parent::onClose($who);

		$this->getHolder()->getLevel()->dropItem($this->getHolder()->add(0.5, 0.5, 0.5), $this->getItem(0));
		$this->getHolder()->getLevel()->dropItem($this->getHolder()->add(0.5, 0.5, 0.5), $this->getItem(1));

		$this->clear(0);
		$this->clear(1);
		$this->clear(2);
	}

}