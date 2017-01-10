<?php



namespace devmine\worlds\sound;

use devmine\server\calculations\Vector3;
use devmine\server\network\protocol\DataPacket;

abstract class Sound extends Vector3{

	/**
	 * @return DataPacket|DataPacket[]
	 */
	abstract public function encode();

}
