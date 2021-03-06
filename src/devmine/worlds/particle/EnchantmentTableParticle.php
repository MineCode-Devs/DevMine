<?php



namespace devmine\worlds\particle;

use devmine\server\calculations\Vector3;

class EnchantmentTableParticle extends GenericParticle{
	public function __construct(Vector3 $pos){
		parent::__construct($pos, Particle::TYPE_ENCHANTMENT_TABLE);
	}
}
