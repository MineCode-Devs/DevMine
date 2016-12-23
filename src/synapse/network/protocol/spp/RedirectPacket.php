<?php


 
namespace synapse\network\protocol\spp;

use devmine\utilities\main\UUID;

class RedirectPacket extends DataPacket{
	const NETWORK_ID = Info::REDIRECT_PACKET;

	/** @var UUID */
	public $uuid;
	public $direct;
	public $mcpeBuffer;

	public function encode(){
		$this->reset();
		$this->putUUID($this->uuid);
		$this->putByte($this->direct ? 1 : 0);
		$this->putString($this->mcpeBuffer);
	}

	public function decode(){
		$this->uuid = $this->getUUID();
		$this->direct = ($this->getByte() == 1) ? true : false;
		$this->mcpeBuffer = $this->getString();
	}
}