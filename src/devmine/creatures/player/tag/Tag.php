<?php



/**
 * All the NBT Tags
 */
namespace devmine\creatures\player\tag;

use devmine\creatures\player\NBT;

abstract class Tag extends \stdClass{

	protected $value;

	public function &getValue(){
		return $this->value;
	}

	public abstract function getType();

	public function setValue($value){
		$this->value = $value;
	}

	abstract public function write(NBT $nbt, bool $network = false);

	abstract public function read(NBT $nbt, bool $network = false);

	public function __toString(){
		return (string) $this->value;
	}
}
