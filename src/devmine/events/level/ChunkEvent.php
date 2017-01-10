<?php



/**
 * Level related events
 */
namespace devmine\events\level;

use devmine\worlds\format\Chunk;

abstract class ChunkEvent extends LevelEvent{
	/** @var Chunk */
	private $chunk;

	/**
	 * @param Chunk $chunk
	 */
	public function __construct(Chunk $chunk){
		parent::__construct($chunk->getProvider()->getLevel());
		$this->chunk = $chunk;
	}

	/**
	 * @return Chunk
	 */
	public function getChunk(){
		return $this->chunk;
	}
}