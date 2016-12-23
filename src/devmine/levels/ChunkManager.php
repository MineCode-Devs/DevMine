<?php



declare(strict_types=1);

namespace devmine\levels;

use devmine\levels\format\FullChunk;

interface ChunkManager{
	/**
	 * Gets the raw block id.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int 0-255
	 */
	public function getBlockIdAt(int $x, int $y, int $z) : int;

	/**
	 * Sets the raw block id.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $id 0-255
	 */
	public function setBlockIdAt(int $x, int $y, int $z, int $id);

	/**
	 * Gets the raw block epilogos
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int 0-15
	 */
	public function getBlockDataAt(int $x, int $y, int $z) : int;

	/**
	 * Sets the raw block epilogos.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $data 0-15
	 */
	public function setBlockDataAt(int $x, int $y, int $z, int $data);

	/**
	 * Gets the raw block light level
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 *
	 * @return int 0-15
	 */
	public function getBlockLightAt($x, $y, $z);

	/**
	 * Updates the light around the block
	 *
	 * @param $x
	 * @param $y
	 * @param $z
	 */
	public function updateBlockLight($x, $y, $z);

	/**
	 * Sets the raw block light level.
	 *
	 * @param int $x
	 * @param int $y
	 * @param int $z
	 * @param int $level 0-15
	 */
	public function setBlockLightAt($x, $y, $z, $level);

	/**
	 * @param int $chunkX
	 * @param int $chunkZ
	 *
	 * @return FullChunk|null
	 */
	public function getChunk(int $chunkX, int $chunkZ);

	/**
	 * @param int $chunkX
	 * @param int $chunkZ
	 * @param FullChunk $chunk
	 */
	public function setChunk(int $chunkX, int $chunkZ, FullChunk $chunk = null);

	/**
	 * Gets the level seed
	 *
	 * @return int
	 */
	public function getSeed();
}