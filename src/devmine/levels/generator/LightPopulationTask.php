<?php



namespace devmine\levels\generator;


use devmine\levels\format\FullChunk;

use devmine\levels\Level;

use devmine\server\tasks\AsyncTask;
use devmine\Server;


class LightPopulationTask extends AsyncTask{

	public $levelId;
	public $chunk;
	public $chunkClass;

	public function __construct(Level $level, FullChunk $chunk){
		$this->levelId = $level->getId();
		$this->chunk = $chunk->toFastBinary();
		$this->chunkClass = get_class($chunk);
	}

	public function onRun(){
		/** @var FullChunk $chunk */
		$chunk = $this->chunkClass;
		$chunk = $chunk::fromFastBinary($this->chunk);
		if($chunk === null){
			//TODO error
			return;
		}

		$chunk->recalculateHeightMap();
		$chunk->populateSkyLight();
		$chunk->setLightPopulated();

		$this->chunk = $chunk->toFastBinary();
	}

	public function onCompletion(Server $server){
		$level = $server->getLevel($this->levelId);
		if($level !== null){
			/** @var FullChunk $chunk */
			$chunk = $this->chunkClass;
			$chunk = $chunk::fromFastBinary($this->chunk, $level->getProvider());
			if($chunk === null){
				//TODO error
				return;
			}
			$level->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
		}
	}
}
