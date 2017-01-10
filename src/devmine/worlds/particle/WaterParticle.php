<?php



namespace devmine\worlds\particle;

use devmine\server\calculations\Vector3;

class WaterParticle extends GenericParticle{
	public function __construct(Vector3 $pos){
		parent::__construct($pos, Particle::TYPE_WATER_WAKE);
	}
}
