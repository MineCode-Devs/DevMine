<?php



namespace devmine\levels\generator\populator;

use devmine\levels\ChunkManager;
use devmine\utilities\main\Random;

class Mineshaft extends Populator{
	private static $DISTANCE = 256;
	private static $VARIATION = 16;
	private static $ODD = 3;
	private static $BASE_Y = 35;
	private static $RAND_Y = 11;

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random){
		if($random->nextRange(0, self::$ODD) === 0){
			//$mineshaft = new Mineshaft($random);
		}
	}

}