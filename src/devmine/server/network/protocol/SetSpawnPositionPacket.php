<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>


class SetSpawnPositionPacket extends DataPacket{
	const NETWORK_ID = Info::SET_SPAWN_POSITION_PACKET;

	public $unknownVarInt;
	public $x;
	public $y;
	public $z;
	public $unknown;
	public $unknownBool;

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putVarInt($this->unknownVarInt);
		$this->putBlockCoords($this->x, $this->y, $this->z);
		$this->putBool($this->unknownBool);
	}

}
