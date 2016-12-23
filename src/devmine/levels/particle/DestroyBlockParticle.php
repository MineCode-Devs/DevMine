<?php



namespace devmine\levels\particle;

use devmine\server\network\protocol\LevelEventPacket;
use devmine\inventory\blocks\Block;
use devmine\server\calculations\Vector3;

class DestroyBlockParticle extends Particle{
	
	protected $data;

	public function __construct(Vector3 $pos, Block $b){
		parent::__construct($pos->x, $pos->y, $pos->z);
		$this->data = $b->getId() + ($b->getDamage() << 12);
	}
	
	public function encode(){
		$pk = new LevelEventPacket;
		$pk->evid = LevelEventPacket::EVENT_PARTICLE_DESTROY;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->data = $this->data;
		
		return $pk;
	}
}
