<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>

class InventoryActionPacket extends DataPacket{
	const NETWORK_ID = Info::INVENTORY_ACTION_PACKET;

	public $unknown; //varint (unsigned)
	public $item;

	public function decode(){
		$this->unknown = $this->getUnsignedVarInt();
		$this->item = $this->getSlot();
	}
	
	public function encode(){
		$this->putUnsignedVarInt($this->unknown);
		$this->putSlot($this->item);
	}
}