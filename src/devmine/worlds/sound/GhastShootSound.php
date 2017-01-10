<?php



namespace devmine\worlds\sound;

use devmine\server\calculations\Vector3;
use devmine\server\network\protocol\LevelEventPacket;

class GhastShootSound extends GenericSound{
	public function __construct(Vector3 $pos, $pitch = 0){
		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_GHAST_SHOOT, $pitch);
	}
}
