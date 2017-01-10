<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>


class DisconnectPacket extends DataPacket{
	const NETWORK_ID = Info::DISCONNECT_PACKET;

	public $hideDisconnectionScreen = false;
	public $message;

	public function decode(){
		$this->hideDisconnectionScreen = $this->getBool();
		$this->message = $this->getString();
		if(!$this->feof()){
			var_dump(strlen($this->buffer) - $this->offset);
			var_dump($buffer);
		}
	}

	public function encode(){
		$this->reset();
		$this->putBool($this->hideDisconnectionScreen);
		$this->putString($this->message);
	}

}
