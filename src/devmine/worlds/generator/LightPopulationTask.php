<?php



namespace devmine\worlds\generator;

use devmine\worlds\format\Chunk;
use devmine\worlds\format\generic\GenericChunk;
use devmine\worlds\Level;
use devmine\server\tasks\AsyncTask;
use devmine\server\server;

class LightPopulationTask extends AsyncTask{

	public $levelId;
	public $chunk;

	public function __construct(Level $level, Chunk $chunk){
		$this->levelId = $level->getId();
		$this->chunk = $chunk->fastSerialize();
	}

	public function onRun(){
		/** @var Chunk $chunk */
		$chunk = GenericChunk::fastDeserialize($this->chunk);
		if($chunk === null){
			//TODO error
			return;
		}

		$chunk->recalculateHeightMap();
		$chunk->populateSkyLight();
		$chunk->setLightPopulated();

		$this->chunk = $chunk->fastSerialize();
	}

	public function onCompletion(Server $server){
		$level = $server->getLevel($this->levelId);
		if($level !== null){
			/** @var Chunk $chunk */
			$chunk = GenericChunk::fastDeserialize($this->chunk, $level->getProvider());
			if($chunk === null){
				//TODO error
				return;
			}
			$level->generateChunkCallback($chunk->getX(), $chunk->getZ(), $chunk);
		}
	}
}
