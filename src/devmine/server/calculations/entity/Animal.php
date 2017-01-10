<?php



namespace devmine\creatures\entities;


abstract class Animal extends Creature implements Ageable{

	public function isBaby(){
		return $this->getDataFlag(self::DATA_FLAGS, self::DATA_FLAG_BABY);
	}
}