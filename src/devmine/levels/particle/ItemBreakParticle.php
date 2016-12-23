<?php



namespace devmine\levels\particle;

use devmine\server\calculations\Vector3;
use devmine\inventory\items\Item;

class ItemBreakParticle extends GenericParticle{
	public function __construct(Vector3 $pos, Item $item){
		parent::__construct($pos, Particle::TYPE_ITEM_BREAK, ($item->getId() << 16) | $item->getDamage());
	}
}
