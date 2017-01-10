<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>


class MobEquipmentPacket extends DataPacket{
	const NETWORK_ID = Info::MOB_EQUIPMENT_PACKET;

	public $eid;
	public $item;
	public $slot;
	public $selectedSlot;
	public $unknown; //byte

	public function decode(){
		$this->eid = $this->getEntityId();
		$this->item = $this->getSlot();
		$this->slot = $this->getByte();
		$this->selectedSlot = $this->getByte();
		$this->unknown = $this->getByte();
	}

	public function encode(){
		$this->reset();
		$this->putEntityId($this->eid);
		$this->putSlot($this->item);
		$this->putByte($this->slot);
		$this->putByte($this->selectedSlot);
		$this->putByte($this->unknown);
	}

}
