<?php



namespace devmine\levels\format\anvil;

use devmine\levels\Level;
use devmine\creatures\player\NBT;
use devmine\server\network\protocol\FullChunkDataPacket;
use devmine\server\tasks\AsyncTask;
use devmine\Server;
use devmine\inventory\solidentity\Spawnable;
use devmine\utilities\main\BinaryStream;


class ChunkRequestTask extends AsyncTask{

	protected $levelId;

	protected $chunk;
	protected $chunkX;
	protected $chunkZ;

	protected $solidentities;

	public function __construct(Level $level, Chunk $chunk){
		$this->levelId = $level->getId();

		$this->chunk = $chunk->toFastBinary();
		$this->chunkX = $chunk->getX();
		$this->chunkZ = $chunk->getZ();

		$solidentities = "";
		$nbt = new NBT(NBT::LITTLE_ENDIAN);
		foreach($chunk->getsolidentities() as $solidentity){
			if($solidentity instanceof Spawnable){
				$nbt->setData($solidentity->getSpawnCompound());
				$solidentities .= $nbt->write();
			}
		}

		$this->solidentities = $solidentities;
	}

	public function onRun(){

		$chunk = Chunk::fromFastBinary($this->chunk);
		$extraData = new BinaryStream();
		$extraData->putLInt(count($chunk->getBlockExtraDataArray()));
		foreach($chunk->getBlockExtraDataArray() as $key => $value){
			$extraData->putLInt($key);
			$extraData->putLShort($value);
		}

		$ordered = $chunk->getBlockIdArray() .
			$chunk->getBlockDataArray() .
			$chunk->getBlockSkyLightArray() .
			$chunk->getBlockLightArray() .
			pack("C*", ...$chunk->getHeightMapArray()) .
			pack("N*", ...$chunk->getBiomeColorArray()) .
			$extraData->getBuffer() .
			$this->solidentities;

		$this->setResult($ordered, false);
	}

	public function onCompletion(Server $server){
		$level = $server->getLevel($this->levelId);
		if($level instanceof Level and $this->hasResult()){
			$level->chunkRequestCallback($this->chunkX, $this->chunkZ, $this->getResult(), FullChunkDataPacket::ORDER_LAYERED);
		}
	}

}