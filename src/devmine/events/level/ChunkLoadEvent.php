<?php



namespace devmine\events\level;

use devmine\worlds\format\Chunk;

/**
 * Called when a Chunk is loaded
 */
class ChunkLoadEvent extends ChunkEvent{
	public static $handlerList = null;

	private $newChunk;

	public function __construct(Chunk $chunk, $newChunk){
		parent::__construct($chunk);
		$this->newChunk = (bool) $newChunk;
	}

	/**
	 * @return bool
	 */
	public function isNewChunk(){
		return $this->newChunk;
	}
}