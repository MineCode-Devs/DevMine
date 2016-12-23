<?php



namespace devmine\creatures\player\tag;

use devmine\creatures\player\NBT;

#include <rules/NBT.h>

class IntArrayTag extends NamedTag{

	public function getType(){
		return NBT::TAG_IntArray;
	}

	public function read(NBT $nbt){
		$size = $nbt->getInt();
		$this->value = array_values(unpack($nbt->endianness === NBT::LITTLE_ENDIAN ? "V*" : "N*", $nbt->get($size * 4)));
	}

	public function write(NBT $nbt){
		$nbt->putInt(count($this->value));
		$nbt->put(pack($nbt->endianness === NBT::LITTLE_ENDIAN ? "V*" : "N*", ...$this->value));
	}

	public function __toString(){
		$str = get_class($this) . "{\n";
		$str .= implode(", ", $this->value);
		return $str . "}";
	}
}