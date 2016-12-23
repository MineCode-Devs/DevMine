<?php



namespace devmine\creatures\player\tag;

use devmine\creatures\player\NBT;

#include <rules/NBT.h>

class LongTag extends NamedTag{

	public function getType(){
		return NBT::TAG_Long;
	}

	public function read(NBT $nbt){
		$this->value = $nbt->getLong();
	}

	public function write(NBT $nbt){
		$nbt->putLong($this->value);
	}
}