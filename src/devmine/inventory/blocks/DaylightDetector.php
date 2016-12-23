<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\Player;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\StringTag;
use devmine\creatures\player\tag\IntTag;
use devmine\inventory\solidentity\solidentity;
use devmine\inventory\solidentity\DLDetector;

class DaylightDetector extends RedstoneSource{
	protected $id = self::DAYLIGHT_SENSOR;
	//protected $hasStartedUpdate = false;

	public function getName() : string{
		return "Daylight Sensor";
	}

	public function getBoundingBox(){
		if($this->boundingBox === null){
			$this->boundingBox = $this->recalculateBoundingBox();
		}
		return $this->boundingBox;
	}

	public function canBeFlowedInto(){
		return false;
	}

	public function canBeActivated() : bool {
		return true;
	}

	/**
	 * @return DLDetector
	 */
	protected function getsolidentity(){
		$t = $this->getLevel()->getsolidentity($this);
		if($t instanceof DLDetector){
			return $t;
		}else{
			$nbt = new CompoundTag("", [
				new StringTag("id", solidentity::DAY_LIGHT_DETECTOR),
				new IntTag("x", $this->x),
				new IntTag("y", $this->y),
				new IntTag("z", $this->z)
			]);
			return solidentity::createsolidentity(solidentity::DAY_LIGHT_DETECTOR, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
		}
	}

	public function onActivate(Item $item, Player $player = null){
		$this->getLevel()->setBlock($this, new DaylightDetectorInverted(), true, true);
		$this->getsolidentity()->onUpdate();
		return true;
	}

	public function isActivated(Block $from = null){
		return $this->getsolidentity()->isActivated();
	}

	public function onBreak(Item $item){
		$this->getLevel()->setBlock($this, new Air());
		if($this->isActivated()) $this->deactivate();
	}

	public function getHardness() {
		return 0.2;
	}

	public function getResistance(){
		return 1;
	}

	public function getDrops(Item $item) : array {
		return [
			[self::DAYLIGHT_SENSOR, 0, 1]
		];
	}
}