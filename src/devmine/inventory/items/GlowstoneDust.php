<?php



namespace devmine\inventory\items;

class GlowstoneDust extends Item{
	public function __construct($meta = 0, $count = 1){
		parent::__construct(self::GLOWSTONE_DUST, $meta, $count, "Glowstone Dust");
	}

}

