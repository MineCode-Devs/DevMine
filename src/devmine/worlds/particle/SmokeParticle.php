<?php



namespace devmine\worlds\particle;

use devmine\server\calculations\Vector3;

class SmokeParticle extends GenericParticle{
	public function __construct(Vector3 $pos, $scale = 0){
		parent::__construct($pos, Particle::TYPE_SMOKE, (int) $scale);
	}
}
