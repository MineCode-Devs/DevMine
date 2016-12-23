<?php



namespace devmine\creatures\player\tag;

use devmine\creatures\player\NBT;

#include <rules/NBT.h>

class StringTag extends NamedTag{
	
	public function getType(){
		return NBT::TAG_String;
	}

	public function read(NBT $nbt){
		$this->value = $nbt->get($nbt->getShort());
	}

	public function write(NBT $nbt){
		$nbt->putShort(strlen($this->value));
		$nbt->put($this->value);
	}
}