<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>


class PlayerFallPacket extends DataPacket{
	
	const NETWORK_ID = Info::PLAYER_FALL_PACKET;
	public $fallDistance;

	public function decode(){
		$this->fallDistance = $this->getLFloat();
	}

	public function encode(){

	}
}
