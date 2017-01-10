<?php



namespace devmine\worlds\particle;

use devmine\server\calculations\Vector3;

class HugeExplodeSeedParticle extends GenericParticle{
	public function __construct(Vector3 $pos){
		parent::__construct($pos, Particle::TYPE_HUGE_EXPLODE_SEED);
	}
}