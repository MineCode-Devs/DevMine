<?php



namespace devmine\levels\particle;

use devmine\server\calculations\Vector3;

class InkParticle extends GenericParticle{
	public function __construct(Vector3 $pos, $scale = 0){
		parent::__construct($pos, Particle::TYPE_INK, $scale);
	}
}
