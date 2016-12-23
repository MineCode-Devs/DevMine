<?php



namespace devmine\levels\format\leveldb;

use devmine\levels\format\generic\BaseFullChunk;
use devmine\levels\format\LevelProvider;
use devmine\creatures\player\NBT;
use devmine\Player;
use devmine\utilities\main\Binary;
use devmine\utilities\main\BinaryStream;

class Chunk extends BaseFullChunk{

	const DATA_LENGTH = 16384 * (2 + 1 + 1 + 1) + 256 + 1024;

	protected $isLightPopulated = false;
	protected $isPopulated = false;
	protected $isGenerated = false;

	public function __construct($level, $chunkX, $chunkZ, $terrain, array $entityData = null, array $solidentityData = null){
		$offset = 0;

		$blocks = substr($terrain, $offset, 32768);
		$offset += 32768;
		$data = substr($terrain, $offset, 16384);
		$offset += 16384;
		$skyLight = substr($terrain, $offset, 16384);
		$offset += 16384;
		$blockLight = substr($terrain, $offset, 16384);
		$offset += 16384;

		$heightMap = [];
		foreach(unpack("C*", substr($terrain, $offset, 256)) as $c){
			$heightMap[] = $c;
		}
		$offset += 256;

		$biomeColors = [];
		foreach(unpack("N*", substr($terrain, $offset, 1024)) as $c){
			$biomeColors[] = $c;
		}
		$offset += 1024;

		parent::__construct($level, $chunkX, $chunkZ, $blocks, $data, $skyLight, $blockLight, $biomeColors, $heightMap, $entityData === null ? [] : $entityData, $solidentityData === null ? [] : $solidentityData);
	}

	public function getBlockId($x, $y, $z){
		return ord($this->blocks{($x << 11) | ($z << 7) | $y});
	}

	public function setBlockId($x, $y, $z, $id){
		$this->blocks{($x << 11) | ($z << 7) | $y} = chr($id);
		$this->hasChanged = true;
	}

	public function getBlockData($x, $y, $z){
		$m = ord($this->data{($x << 10) | ($z << 6) | ($y >> 1)});
		if(($y & 1) === 0){
			return $m & 0x0F;
		}else{
			return $m >> 4;
		}
	}

	public function setBlockData($x, $y, $z, $data){
		$i = ($x << 10) | ($z << 6) | ($y >> 1);
		$old_m = ord($this->data{$i});
		if(($y & 1) === 0){
			$this->data{$i} = chr(($old_m & 0xf0) | ($data & 0x0f));
		}else{
			$this->data{$i} = chr((($data & 0x0f) << 4) | ($old_m & 0x0f));
		}
		$this->hasChanged = true;
	}

	public function getFullBlock($x, $y, $z){
		$i = ($x << 11) | ($z << 7) | $y;
		if(($y & 1) === 0){
			return (ord($this->blocks{$i}) << 4) | (ord($this->data{$i >> 1}) & 0x0F);
		}else{
			return (ord($this->blocks{$i}) << 4) | (ord($this->data{$i >> 1}) >> 4);
		}
	}

	public function getBlock($x, $y, $z, &$blockId, &$meta = null){
		$full = $this->getFullBlock($x, $y, $z);
		$blockId = $full >> 4;
		$meta = $full & 0x0f;
	}

	public function setBlock($x, $y, $z, $blockId = null, $meta = null){
		$i = ($x << 11) | ($z << 7) | $y;

		$changed = false;

		if($blockId !== null){
			$blockId = chr($blockId);
			if($this->blocks{$i} !== $blockId){
				$this->blocks{$i} = $blockId;
				$changed = true;
			}
		}

		if($meta !== null){
			$i >>= 1;
			$old_m = ord($this->data{$i});
			if(($y & 1) === 0){
				$this->data{$i} = chr(($old_m & 0xf0) | ($meta & 0x0f));
				if(($old_m & 0x0f) !== $meta){
					$changed = true;
				}
			}else{
				$this->data{$i} = chr((($meta & 0x0f) << 4) | ($old_m & 0x0f));
				if((($old_m & 0xf0) >> 4) !== $meta){
					$changed = true;
				}
			}
		}

		if($changed){
			$this->hasChanged = true;
		}

		return $changed;
	}

	public function getBlockSkyLight($x, $y, $z){
		$sl = ord($this->skyLight{($x << 10) | ($z << 6) | ($y >> 1)});
		if(($y & 1) === 0){
			return $sl & 0x0F;
		}else{
			return $sl >> 4;
		}
	}

	public function setBlockSkyLight($x, $y, $z, $level){
		$i = ($x << 10) | ($z << 6) | ($y >> 1);
		$old_sl = ord($this->skyLight{$i});
		if(($y & 1) === 0){
			$this->skyLight{$i} = chr(($old_sl & 0xf0) | ($level & 0x0f));
		}else{
			$this->skyLight{$i} = chr((($level & 0x0f) << 4) | ($old_sl & 0x0f));
		}
		$this->hasChanged = true;
	}

	public function getBlockLight($x, $y, $z){
		$l = ord($this->blockLight{($x << 10) | ($z << 6) | ($y >> 1)});
		if(($y & 1) === 0){
			return $l & 0x0F;
		}else{
			return $l >> 4;
		}
	}

	public function setBlockLight($x, $y, $z, $level){
		$i = ($x << 10) | ($z << 6) | ($y >> 1);
		$old_l = ord($this->blockLight{$i});
		if(($y & 1) === 0){
			$this->blockLight{$i} = chr(($old_l & 0xf0) | ($level & 0x0f));
		}else{
			$this->blockLight{$i} = chr((($level & 0x0f) << 4) | ($old_l & 0x0f));
		}
		$this->hasChanged = true;
	}

	public function getBlockIdColumn($x, $z){
		return substr($this->blocks, ($x << 11) + ($z << 7), 128);
	}

	public function getBlockDataColumn($x, $z){
		return substr($this->data, ($x << 10) + ($z << 6), 64);
	}

	public function getBlockSkyLightColumn($x, $z){
		return substr($this->skyLight, ($x << 10) + ($z << 6), 64);
	}

	public function getBlockLightColumn($x, $z){
		return substr($this->blockLight, ($x << 10) + ($z << 6), 64);
	}

	/**
	 * @return bool
	 */
	public function isLightPopulated(){
		return $this->isLightPopulated;
	}

	/**
	 * @param int $value
	 */
	public function setLightPopulated($value = 1){
		$this->isLightPopulated = (bool) $value;
	}

	/**
	 * @return bool
	 */
	public function isPopulated(){
		return $this->isPopulated;
	}

	/**
	 * @param int $value
	 */
	public function setPopulated($value = 1){
		$this->isPopulated = (bool) $value;
	}

	/**
	 * @return bool
	 */
	public function isGenerated(){
		return $this->isGenerated;
	}

	/**
	 * @param int $value
	 */
	public function setGenerated($value = 1){
		$this->isGenerated = (bool) $value;
	}

	public static function fromFastBinary($data, LevelProvider $provider = null){
		return self::fromBinary($data, $provider);
	}

	/**
	 * @param string        $data
	 * @param LevelProvider $provider
	 *
	 * @return Chunk
	 */
	public static function fromBinary($data, LevelProvider $provider = null){
		try{
			$chunkX = Binary::readLInt(substr($data, 0, 4));
			$chunkZ = Binary::readLInt(substr($data, 4, 4));
			$chunkData = substr($data, 8, -1);

			$flags = ord(substr($data, -1));

			$entities = null;
			$solidentities = null;
			$extraData = [];

			if($provider instanceof LevelDB){
				$nbt = new NBT(NBT::LITTLE_ENDIAN);

				$entityData = $provider->getDatabase()->get(substr($data, 0, 8) . LevelDB::ENTRY_ENTITIES);
				if($entityData !== false and strlen($entityData) > 0){
					$nbt->read($entityData, true);
					$entities = $nbt->getData();
					if(!is_array($entities)){
						$entities = [$entities];
					}
				}
				$solidentityData = $provider->getDatabase()->get(substr($data, 0, 8) . LevelDB::ENTRY_solidentities);
				if($solidentityData !== false and strlen($solidentityData) > 0){
					$nbt->read($solidentityData, true);
					$solidentities = $nbt->getData();
					if(!is_array($solidentities)){
						$solidentities = [$solidentities];
					}
				}
				$solidentityData = $provider->getDatabase()->get(substr($data, 0, 8) . LevelDB::ENTRY_EXTRA_DATA);
				if($solidentityData !== false and strlen($solidentityData) > 0){
					$stream = new BinaryStream($solidentityData);
					$count = $stream->getInt();
					for($i = 0; $i < $count; ++$i){
						$key = $stream->getInt();
						$value = $stream->getShort(false);
						$extraData[$key] = $value;
					}
				}
			}

			$chunk = new Chunk($provider instanceof LevelProvider ? $provider : LevelDB::class, $chunkX, $chunkZ, $chunkData, $entities, $solidentities);
			if($flags & 0x01){
				$chunk->setGenerated();
			}
			if($flags & 0x02){
				$chunk->setPopulated();
			}
			if($flags & 0x04){
				$chunk->setLightPopulated();
			}
			return $chunk;
		}catch(\Throwable $e){
			return null;
		}
	}

	public function toFastBinary(){
		return $this->toBinary(false);
	}

	public function toBinary($saveExtra = false){
		$chunkIndex = LevelDB::chunkIndex($this->getX(), $this->getZ());

		$provider = $this->getProvider();
		if($saveExtra and $provider instanceof LevelDB){
			$nbt = new NBT(NBT::LITTLE_ENDIAN);
			$entities = [];

			foreach($this->getEntities() as $entity){
				if(!($entity instanceof Player) and !$entity->closed){
					$entity->saveNBT();
					$entities[] = $entity->namedtag;
				}
			}

			if(count($entities) > 0){
				$nbt->setData($entities);
				$provider->getDatabase()->put($chunkIndex . LevelDB::ENTRY_ENTITIES, $nbt->write());
			}else{
				$provider->getDatabase()->delete($chunkIndex . LevelDB::ENTRY_ENTITIES);
			}


			$solidentities = [];

			foreach($this->getsolidentities() as $solidentity){
				if(!$solidentity->closed){
					$solidentity->saveNBT();
					$solidentities[] = $solidentity->namedtag;
				}
			}

			if(count($solidentities) > 0){
				$nbt->setData($solidentities);
				$provider->getDatabase()->put($chunkIndex . LevelDB::ENTRY_solidentities, $nbt->write());
			}else{
				$provider->getDatabase()->delete($chunkIndex . LevelDB::ENTRY_solidentities);
			}

			if(count($this->getBlockExtraDataArray()) > 0){
				$extraData = new BinaryStream();
				$extraData->putInt(count($this->getBlockExtraDataArray()));
				foreach($this->getBlockExtraDataArray() as $key => $value){
					$extraData->putInt($key);
					$extraData->putShort($value);
				}
				$provider->getDatabase()->put($chunkIndex . LevelDB::ENTRY_EXTRA_DATA, $extraData->getBuffer());
			}else{
				$provider->getDatabase()->delete($chunkIndex . LevelDB::ENTRY_EXTRA_DATA);
			}

		}

		$heightmap = pack("C*", ...$this->getHeightMapArray());
		$biomeColors = pack("N*", ...$this->getBiomeColorArray());

		return $chunkIndex .
			$this->getBlockIdArray() .
			$this->getBlockDataArray() .
			$this->getBlockSkyLightArray() .
			$this->getBlockLightArray() .
			$heightmap .
			$biomeColors . chr(
				($this->isLightPopulated() ? 0x04 : 0) | ($this->isPopulated() ? 0x02 : 0) | ($this->isGenerated() ? 0x01 : 0)
			);
	}

	/**
	 * @param int           $chunkX
	 * @param int           $chunkZ
	 * @param LevelProvider $provider
	 *
	 * @return Chunk
	 */
	public static function getEmptyChunk($chunkX, $chunkZ, LevelProvider $provider = null){
		try{
			$chunk = new Chunk($provider instanceof LevelProvider ? $provider : LevelDB::class, $chunkX, $chunkZ, str_repeat("\x00", self::DATA_LENGTH));
			$chunk->skyLight = str_repeat("\xff", 16384);
			return $chunk;
		}catch(\Throwable $e){
			return null;
		}
	}
}
