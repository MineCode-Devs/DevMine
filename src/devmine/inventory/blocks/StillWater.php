<?php



namespace devmine\inventory\blocks;

use devmine\worlds\Level;

class StillWater extends Water{

	protected $id = self::STILL_WATER;

	public function onUpdate($type){
		if($type !== Level::BLOCK_UPDATE_SCHEDULED){
			return parent::onUpdate($type);
		}
		return false;
	}

	public function getName() : string{
		return "Still Water";
	}
}
