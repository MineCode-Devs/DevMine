<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>
use devmine\utilities\main\Binary;

class BossEventPacket extends DataPacket{
	const NETWORK_ID = Info::BOSS_EVENT_PACKET;
  	public $eid;
	public $type;

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putVarInt($this->eid);
		$this->putUnsignedVarInt($this->type);
	}
}
