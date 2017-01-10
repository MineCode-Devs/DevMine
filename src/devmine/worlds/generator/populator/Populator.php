<?php



/**
 * All the Object populator classes
 */
namespace devmine\worlds\generator\populator;

use devmine\worlds\ChunkManager;
use devmine\utilities\main\Random;

abstract class Populator{
	public abstract function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random);
}