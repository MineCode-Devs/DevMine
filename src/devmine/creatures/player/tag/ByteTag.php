<?php



namespace devmine\creatures\player\tag;

use devmine\creatures\player\NBT;

#include <rules/NBT.h>

class ByteTag extends NamedTag{

	public function getType(){
		return NBT::TAG_Byte;
	}

	public function read(NBT $nbt){
		$this->value = $nbt->getByte();
	}

	public function write(NBT $nbt){
		$nbt->putByte($this->value);
	}
}