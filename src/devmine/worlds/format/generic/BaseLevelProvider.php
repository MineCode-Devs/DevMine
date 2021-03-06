<?php



declare(strict_types = 1);

namespace devmine\worlds\format\generic;

use devmine\worlds\format\LevelProvider;
use devmine\worlds\generator\Generator;
use devmine\worlds\Level;
use devmine\server\calculations\Vector3;
use devmine\creatures\player\NBT;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\LongTag;
use devmine\creatures\player\tag\StringTag;
use devmine\utilities\main\ChunkException;
use devmine\utilities\main\LevelException;

abstract class BaseLevelProvider implements LevelProvider{
	/** @var Level */
	protected $level;
	/** @var string */
	protected $path;
	/** @var CompoundTag */
	protected $levelData;

	public function __construct(Level $level, string $path){
		$this->level = $level;
		$this->path = $path;
		if(!file_exists($this->path)){
			mkdir($this->path, 0777, true);
		}
		$nbt = new NBT(NBT::BIG_ENDIAN);
		$nbt->readCompressed(file_get_contents($this->getPath() . "level.dat"));
		$levelData = $nbt->getData();
		if($levelData->Data instanceof CompoundTag){
			$this->levelData = $levelData->Data;
		}else{
			throw new LevelException("Invalid level.dat");
		}

		if(!isset($this->levelData->generatorName)){
			$this->levelData->generatorName = new StringTag("generatorName", Generator::getGenerator("DEFAULT"));
		}

		if(!isset($this->levelData->generatorOptions)){
			$this->levelData->generatorOptions = new StringTag("generatorOptions", "");
		}
	}

	public function getPath() : string{
		return $this->path;
	}

	public function getServer(){
		return $this->level->getServer();
	}

	public function getLevel(){
		return $this->level;
	}

	public function getName() : string{
		return $this->levelData["LevelName"];
	}

	public function getTime(){
		return $this->levelData["Time"];
	}

	public function setTime($value){
		$this->levelData->Time = new LongTag("Time", $value);
	}

	public function getSeed(){
		return $this->levelData["RandomSeed"];
	}

	public function setSeed($value){
		$this->levelData->RandomSeed = new LongTag("RandomSeed", $value);
	}

	public function getSpawn() : Vector3{
		return new Vector3((float) $this->levelData["SpawnX"], (float) $this->levelData["SpawnY"], (float) $this->levelData["SpawnZ"]);
	}

	public function setSpawn(Vector3 $pos){
		$this->levelData->SpawnX = new IntTag("SpawnX", (int) $pos->x);
		$this->levelData->SpawnY = new IntTag("SpawnY", (int) $pos->y);
		$this->levelData->SpawnZ = new IntTag("SpawnZ", (int) $pos->z);
	}

	public function doGarbageCollection(){

	}

	/**
	 * @return CompoundTag
	 */
	public function getLevelData() : CompoundTag{
		return $this->levelData;
	}

	public function saveLevelData(){
		$nbt = new NBT(NBT::BIG_ENDIAN);
		$nbt->setData(new CompoundTag("", [
			"Data" => $this->levelData
		]));
		$buffer = $nbt->writeCompressed();
		file_put_contents($this->getPath() . "level.dat", $buffer);
	}

	public function requestChunkTask(int $x, int $z){
		$chunk = $this->getChunk($x, $z, false);
		if(!($chunk instanceof GenericChunk)){
			throw new ChunkException("Invalid Chunk sent");
		}

		$this->getLevel()->chunkRequestCallback($x, $z, $chunk->networkSerialize());
	}
}
