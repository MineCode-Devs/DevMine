<?php



namespace devmine\server\network\protocol;

class ItemFrameDropItemPacket extends DataPacket{

	const NETWORK_ID = Info::ITEM_FRAME_DROP_ITEM_PACKET;

	public $x;
	public $y;
	public $z;
	public $dropItem;

	public function decode(){
		$this->z = $this->getInt();
		$this->y = $this->getInt();
		$this->x = $this->getInt();
		$this->dropItem = $this->getSlot();
	}

	public function encode(){
	}
}