<?php



namespace devmine\worlds\particle;

use devmine\server\calculations\Vector3;
use devmine\server\network\protocol\LevelEventPacket;

class MobSpawnParticle extends Particle{

	protected $width;
	protected $height;

	public function __construct(Vector3 $pos, $width = 0, $height = 0){
		parent::__construct($pos->x, $pos->y, $pos->z);
		$this->width = $width;
		$this->height = $height;
	}

	public function encode(){
		$pk = new LevelEventPacket;
		$pk->evid = LevelEventPacket::EVENT_PARTICLE_SPAWN;
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->data = ($this->width & 0xff) + (($this->height & 0xff) << 8);

		return $pk;
	}
}
