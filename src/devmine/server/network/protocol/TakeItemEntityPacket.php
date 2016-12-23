<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>


class TakeItemEntityPacket extends DataPacket{
	const NETWORK_ID = Info::TAKE_ITEM_ENTITY_PACKET;

	public $target;
	public $eid;

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putLong($this->target);
		$this->putLong($this->eid);
	}

}
