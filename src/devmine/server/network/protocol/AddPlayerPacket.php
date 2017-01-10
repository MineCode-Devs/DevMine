<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>

#ifndef COMPILE
use devmine\utilities\main\Binary;

#endif

class AddPlayerPacket extends DataPacket{
	const NETWORK_ID = Info::ADD_PLAYER_PACKET;

	public $uuid;
	public $username;
	public $eid;
	public $x;
	public $y;
	public $z;
	public $speedX;
	public $speedY;
	public $speedZ;
	public $pitch;
	public $yaw;
	public $item;
	public $metadata = [];

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putUUID($this->uuid);
		$this->putString($this->username);
		$this->putEntityId($this->eid); //EntityUniqueID
		$this->putEntityId($this->eid); //EntityRuntimeID
		$this->putVector3f($this->x, $this->y, $this->z);
		$this->putVector3f($this->speedX, $this->speedY, $this->speedZ);
		//TODO: check these are in the right order
		$this->putLFloat($this->yaw);
		$this->putLFloat($this->yaw); //TODO headrot
		$this->putLFloat($this->pitch);
		$this->putSlot($this->item);

		$meta = Binary::writeMetadata($this->metadata);
		$this->put($meta);
	}

}
