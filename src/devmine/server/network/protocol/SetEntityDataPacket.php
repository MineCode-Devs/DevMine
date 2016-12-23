<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>

#ifndef COMPILE
use devmine\utilities\main\Binary;

#endif

class SetEntityDataPacket extends DataPacket{
	const NETWORK_ID = Info::SET_ENTITY_DATA_PACKET;

	public $eid;
	public $epilogos;

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putLong($this->eid);
		$meta = Binary::writeepilogos($this->epilogos);
		$this->put($meta);
	}

}
