<?php



namespace devmine\inventory\blocks;

use devmine\levels\Level;

class StillWater extends Water{

	protected $id = self::STILL_WATER;

	public function onUpdate($type){
		if($type == Level::BLOCK_UPDATE_NORMAL){
			parent::onUpdate($type);
		}
	}

	public function getName() : string{
		return "Still Water";
	}
}