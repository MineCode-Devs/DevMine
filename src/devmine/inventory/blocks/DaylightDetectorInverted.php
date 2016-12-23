<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\Player;

class DaylightDetectorInverted extends DaylightDetector{
	protected $id = self::DAYLIGHT_SENSOR_INVERTED;

	public function onActivate(Item $item, Player $player = null){
		$this->getLevel()->setBlock($this, new DaylightDetector(), true, true);
		$this->getsolidentity()->onUpdate();
		return true;
	}
}