<?php



namespace devmine\creatures\player\tag;

use devmine\creatures\player\NBT;

class EndTag extends Tag{

	public function getType(){
		return NBT::TAG_End;
	}

	public function read(NBT $nbt){

	}

	public function write(NBT $nbt){

	}
}