<?php



namespace devmine\inventory\layout;

use devmine\server\calculations\Vector3;
use devmine\server\network\Network;
use devmine\server\network\protocol\ContainerClosePacket;
use devmine\server\network\protocol\ContainerOpenPacket;
use devmine\Player;

abstract class ContainerInventory extends BaseInventory{
	public function onOpen(Player $who){
		parent::onOpen($who);
		$pk = new ContainerOpenPacket();
		$pk->windowid = $who->getWindowId($this);
		$pk->type = $this->getType()->getNetworkType();
		$pk->slots = $this->getSize();
		$holder = $this->getHolder();
		if($holder instanceof Vector3){
			$pk->x = $holder->getX();
			$pk->y = $holder->getY();
			$pk->z = $holder->getZ();
		}else{
			$pk->x = $pk->y = $pk->z = 0;
		}

		$who->dataPacket($pk);

		$this->sendContents($who);
	}

	public function onClose(Player $who){
		$pk = new ContainerClosePacket();
		$pk->windowid = $who->getWindowId($this);
		$who->dataPacket($pk);
		parent::onClose($who);
	}
}