<?php



namespace devmine\levels\particle;

use devmine\server\calculations\Vector3;

class CriticalParticle extends GenericParticle{
	public function __construct(Vector3 $pos, $scale = 2){
		parent::__construct($pos, Particle::TYPE_CRITICAL, $scale);
	}
}
