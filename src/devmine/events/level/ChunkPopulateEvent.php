<?php



namespace devmine\events\level;

/**
 * Called when a Chunk is populated (after receiving it on the main thread)
 */
class ChunkPopulateEvent extends ChunkEvent{
	public static $handlerList = null;
}