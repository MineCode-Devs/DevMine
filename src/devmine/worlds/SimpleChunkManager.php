<?php



declare(strict_types = 1);

namespace devmine\worlds;

use devmine\worlds\format\Chunk;

class SimpleChunkManager implements ChunkManager{

	/** @var Chunk[] */
	protected $chunks = [];

	protected $seed;

	public function __construct($seed){
		$this->seed = $seed;
	}

	/**
	 * Gets the raw block id.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int 0-255
	 */
	public function getBlockIdAt(int $x, int $y, int $z) : int{
		if($chunk = $this->getChunk($x >> 4, $z >> 4)){
			return $chunk->getBlockId($x & 0xf, $y & Level::Y_MASK, $z & 0xf);
		}
		return 0;
	}

	/**
	 * Sets the raw block id.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $id 0-255
	 */
	public function setBlockIdAt(int $x, int $y, int $z, int $id){
		if($chunk = $this->getChunk($x >> 4, $z >> 4)){
			$chunk->setBlockId($x & 0xf, $y & Level::Y_MASK, $z & 0xf, $id);
		}
	}

	/**
	 * Gets the raw block metadata
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int 0-15
	 */
	public function getBlockDataAt(int $x, int $y, int $z) : int{
		if($chunk = $this->getChunk($x >> 4, $z >> 4)){
			return $chunk->getBlockData($x & 0xf, $y & Level::Y_MASK, $z & 0xf);
		}
		return 0;
	}

	/**
	 * Sets the raw block metadata.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $data 0-15
	 */
	public function setBlockDataAt(int $x, int $y, int $z, int $data){
		if($chunk = $this->getChunk($x >> 4, $z >> 4)){
			$chunk->setBlockData($x & 0xf, $y & Level::Y_MASK, $z & 0xf, $data);
		}
	}

	/**
	 * @param int $chunkX
	 * @param int $chunkZ
	 *
	 * @return Chunk|null
	 */
	public function getChunk(int $chunkX, int $chunkZ){
		return $this->chunks[Level::chunkHash($chunkX, $chunkZ)] ?? null;
	}

	/**
	 * @param int   $chunkX
	 * @param int   $chunkZ
	 * @param Chunk $chunk
	 */
	public function setChunk(int $chunkX, int $chunkZ, Chunk $chunk = null){
		if($chunk === null){
			unset($this->chunks[Level::chunkHash($chunkX, $chunkZ)]);
			return;
		}
		$this->chunks[Level::chunkHash($chunkX, $chunkZ)] = $chunk;
	}

	public function cleanChunks(){
		$this->chunks = [];
	}

	/**
	 * Gets the level seed
	 *
	 * @return int
	 */
	public function getSeed(){
		return $this->seed;
	}
}