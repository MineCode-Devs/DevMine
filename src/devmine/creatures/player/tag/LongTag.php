<?php



namespace devmine\creatures\player\tag;

use devmine\creatures\player\NBT;

#include <rules/NBT.h>

class LongTag extends NamedTag{

	public function getType(){
		return NBT::TAG_Long;
	}

	//TODO: check if this also changed to varint

	public function read(NBT $nbt, bool $network = false){
		$this->value = $nbt->getLong();
	}

	public function write(NBT $nbt, bool $network = false){
		$nbt->putLong($this->value);
	}
}