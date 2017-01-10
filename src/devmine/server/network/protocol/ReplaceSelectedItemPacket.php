<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>


class ReplaceSelectedItemPacket extends DataPacket{
	const NETWORK_ID = Info::REPLACE_SELECTED_ITEM_PACKET;

	public $item;

	public function decode(){

	}

	public function encode(){
		$this->reset();
		$this->putSlot($this->item);
	}

}