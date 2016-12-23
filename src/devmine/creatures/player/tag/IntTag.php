<?php



namespace devmine\creatures\player\tag;

use devmine\creatures\player\NBT;

#include <rules/NBT.h>

class IntTag extends NamedTag{

	public function getType(){
		return NBT::TAG_Int;
	}

	public function read(NBT $nbt){
		$this->value = $nbt->getInt();
	}

	public function write(NBT $nbt){
		$nbt->putInt($this->value);
	}
}