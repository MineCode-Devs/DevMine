<?php



namespace devmine\server\network\protocol;

class PlayerInputPacket extends DataPacket{
	const NETWORK_ID = Info::PLAYER_INPUT_PACKET;

	public $motX;
	public $motY;

	public $jumping;
	public $sneaking;
	
	public $unknownBool;
	public $unknownBool1;
	public $unknownBool2;

	public function decode(){
		$this->motX = $this->getFloat();
		$this->motY = $this->getFloat();
		$flags = $this->getByte();
		$this->unknownBool = $this->getBool();
		$this->unknownBool1 = $this->getBool();
		$this->unknownBool2 = $this->getBool();
		$this->jumping = (($flags & 0x80) > 0);
		$this->sneaking = (($flags & 0x40) > 0);
	}

	public function encode(){

	}

}
