<?php



namespace devmine\worlds\particle;

use devmine\server\calculations\Vector3;

class RedstoneParticle extends GenericParticle{
	public function __construct(Vector3 $pos, $lifetime = 1){
		parent::__construct($pos, Particle::TYPE_REDSTONE, $lifetime);
	}
}
