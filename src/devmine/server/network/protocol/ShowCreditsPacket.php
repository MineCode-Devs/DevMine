<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>


class ShowCreditsPacket extends DataPacket{
	const NETWORK_ID = Info::SHOW_CREDITS_PACKET;

	public $eid;

	public function decode(){
		$this->eid = $this->getEntityId(); //EntityRuntimeID
	}

	public function encode(){
		$this->reset();
		$this->putEntityId($this->eid); //EntityRuntimeID
	}

}
