<?php



namespace devmine\levels\format\generic;

use devmine\levels\format\LevelProvider;
use devmine\levels\generator\Generator;
use devmine\levels\Level;
use devmine\server\calculations\Vector3;
use devmine\creatures\player\NBT;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\LongTag;
use devmine\creatures\player\tag\StringTag;
use devmine\utilities\main\LevelException;

abstract class BaseLevelProvider implements LevelProvider{
	/** @var Level */
	protected $level;
	/** @var string */
	protected $path;
	/** @var CompoundTag */
	protected $levelData;

	public function __construct(Level $level, $path){
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

	public function getPath(){
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
		$this->levelData->Time = new IntTag("Time", (int) $value);
	}

	public function getSeed(){
		return $this->levelData["RandomSeed"];
	}

	public function setSeed($value){
		$this->levelData->RandomSeed = new LongTag("RandomSeed", (int) $value);
	}

	public function getSpawn(){
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
	public function getLevelData(){
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


}