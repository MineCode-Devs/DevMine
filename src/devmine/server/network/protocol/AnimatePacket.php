<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>


class AnimatePacket extends DataPacket{
	const NETWORK_ID = Info::ANIMATE_PACKET;

	public $action;
	public $eid;
	public $unknownFloat; //TODO: find out what this is for (maybe an amplifier?)

	public function decode(){
		$this->action = $this->getVarInt();
		$this->eid = $this->getEntityId();
		if(!$this->feof()){
			$this->unknownFloat = $this->getLFloat(); //TODO: find out when this is sent (not always! >:-[)
		}
	}

	public function encode(){
		$this->reset();
		$this->getVarInt($this->action);
		$this->putEntityId($this->eid);
		$this->putLFloat($this->unknownFloat);
	}

}
