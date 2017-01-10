<?php



namespace devmine\creatures\player\tag;

use devmine\creatures\player\NBT;

#include <rules/NBT.h>

class StringTag extends NamedTag{
	
	public function getType(){
		return NBT::TAG_String;
	}

	public function read(NBT $nbt, bool $network = false){
		$this->value = $nbt->getString($network);
	}

	public function write(NBT $nbt, bool $network = false){
		$nbt->putString($this->value, $network);
	}
}