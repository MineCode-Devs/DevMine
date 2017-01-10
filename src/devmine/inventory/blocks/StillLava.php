<?php



namespace devmine\inventory\blocks;

use devmine\worlds\Level;

class StillLava extends Lava{

	protected $id = self::STILL_LAVA;

	public function onUpdate($type){
		if($type !== Level::BLOCK_UPDATE_SCHEDULED){
			return parent::onUpdate($type);
		}
		return false;
	}

	public function getName() : string{
		return "Still Lava";
	}

}
