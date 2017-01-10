<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>


use devmine\worlds\Level;

class SetTimePacket extends DataPacket{
	const NETWORK_ID = Info::SET_TIME_PACKET;

	public $time;
	public $started = true;

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putVarInt($this->time);
		$this->putBool($this->started);
	}

}