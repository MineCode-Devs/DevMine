<?php



/**
 * All the Object populator classes
 */
namespace devmine\levels\generator\populator;

use devmine\levels\ChunkManager;
use devmine\utilities\main\Random;

abstract class Populator{
	public abstract function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random);
}