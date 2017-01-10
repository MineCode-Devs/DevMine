<?php



namespace devmine\events\level;

use devmine\events\Cancellable;

/**
 * Called when a Chunk is unloaded
 */
class ChunkUnloadEvent extends ChunkEvent implements Cancellable{
	public static $handlerList = null;
}