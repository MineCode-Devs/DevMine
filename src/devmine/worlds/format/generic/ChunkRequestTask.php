<?php



namespace devmine\worlds\format\generic;

use devmine\worlds\format\Chunk;
use devmine\worlds\Level;
use devmine\creatures\player\NBT;
use devmine\server\tasks\AsyncTask;
use devmine\server\server;
use devmine\inventory\solidentity\Spawnable;

class ChunkRequestTask extends AsyncTask{

	protected $levelId;

	protected $chunk;
	protected $chunkX;
	protected $chunkZ;

	protected $tiles;

	public function __construct(Level $level, Chunk $chunk){
		$this->levelId = $level->getId();

		$this->chunk = $chunk->fastSerialize();
		$this->chunkX = $chunk->getX();
		$this->chunkZ = $chunk->getZ();

		//TODO: serialize tiles with chunks
		$tiles = "";
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		foreach($chunk->getTiles() as $tile){
			if($tile instanceof Spawnable){
				$nbt->setData($tile->getSpawnCompound());
				$tiles .= $nbt->write(true);
			}
		}

		$this->tiles = $tiles;
	}

	public function onRun(){
		$chunk = GenericChunk::fastDeserialize($this->chunk);

		$ordered = $chunk->networkSerialize();

		$this->setResult($ordered, false);
	}

	public function onCompletion(Server $server){
		$level = $server->getLevel($this->levelId);
		if($level instanceof Level and $this->hasResult()){
			$level->chunkRequestCallback($this->chunkX, $this->chunkZ, $this->getResult());
		}
	}

}