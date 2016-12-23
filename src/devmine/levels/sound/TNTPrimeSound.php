<?php

/**
 * Author: Pub4Game
 * Opendevmine Project
*/

namespace devmine\levels\sound;

use devmine\server\calculations\Vector3;
use devmine\server\network\protocol\LevelEventPacket;

class TNTPrimeSound extends GenericSound{
	public function __construct(Vector3 $pos, $pitch = 0){
		parent::__construct($pos, LevelEventPacket::EVENT_SOUND_TNT, $pitch);
	}
}
