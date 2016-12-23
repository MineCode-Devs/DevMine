<?php



namespace devmine\inventory\items;

use devmine\creatures\entities\Effect;

class SpiderEye extends Food{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::SPIDER_EYE, $meta, $count, "Spider Eye");
	}

	public function getFoodRestore() : int{
		return 2;
	}

	public function getSaturationRestore() : float{
		return 3.2;
	}

	public function getAdditionalEffects() : array{
		return [Effect::getEffect(Effect::POISON)->setDuration(80)];
	}
}
