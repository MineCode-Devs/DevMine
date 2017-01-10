<?php



declare(strict_types = 1);

namespace devmine\worlds\format\region;

use devmine\worlds\format\generic\GenericChunk;
use devmine\worlds\format\generic\SubChunk;
use devmine\creatures\player\NBT;
use devmine\creatures\player\tag\{
	ByteArrayTag,
	ByteTag,
	CompoundTag,
	IntArrayTag,
	IntTag,
	ListTag,
	LongTag
};
use devmine\creatures\player;
use devmine\utilities\main\ChunkException;
use devmine\utilities\main\MainLogger;

/**
 * This format is exactly the same as the PC Anvil format, with the only difference being that the stored data order
 * is XZY instead of YZX for more performance loading and saving worlds.
 */
class PMAnvil extends Anvil{

	const REGION_FILE_EXTENSION = "mcapm";

	public function nbtSerialize(GenericChunk $chunk) : string{
		$nbt = new CompoundTag("Level", []);
		$nbt->xPos = new IntTag("xPos", $chunk->getX());
		$nbt->zPos = new IntTag("zPos", $chunk->getZ());

		$nbt->V = new ByteTag("V", 1);
		$nbt->LastUpdate = new LongTag("LastUpdate", 0); //TODO
		$nbt->InhabitedTime = new LongTag("InhabitedTime", 0); //TODO
		$nbt->TerrainPopulated = new ByteTag("TerrainPopulated", $chunk->isPopulated());
		$nbt->LightPopulated = new ByteTag("LightPopulated", $chunk->isLightPopulated());

		$nbt->Sections = new ListTag("Sections", []);
		$nbt->Sections->setTagType(NBT::TAG_Compound);
		$subChunks = -1;
		foreach($chunk->getSubChunks() as $y => $subChunk){
			if($subChunk->isEmpty()){
				continue;
			}
			$nbt->Sections[++$subChunks] = new CompoundTag(null, [
				"Y"          => new ByteTag("Y", $y),
				"Blocks"     => new ByteArrayTag("Blocks",     $subChunk->getBlockIdArray()),
				"Data"       => new ByteArrayTag("Data",       $subChunk->getBlockDataArray()),
				"SkyLight"   => new ByteArrayTag("SkyLight",   $subChunk->getSkyLightArray()),
				"BlockLight" => new ByteArrayTag("BlockLight", $subChunk->getBlockLightArray())
			]);
		}

		$nbt->Biomes = new ByteArrayTag("Biomes", $chunk->getBiomeIdArray());
		$nbt->HeightMap = new IntArrayTag("HeightMap", $chunk->getHeightMapArray());

		$entities = [];

		foreach($chunk->getEntities() as $entity){
			if(!($entity instanceof Player) and !$entity->closed){
				$entity->saveNBT();
				$entities[] = $entity->namedtag;
			}
		}

		$nbt->Entities = new ListTag("Entities", $entities);
		$nbt->Entities->setTagType(NBT::TAG_Compound);

		$tiles = [];
		foreach($chunk->getTiles() as $tile){
			$tile->saveNBT();
			$tiles[] = $tile->namedtag;
		}

		$nbt->TileEntities = new ListTag("TileEntities", $tiles);
		$nbt->TileEntities->setTagType(NBT::TAG_Compound);

		//TODO: TileTicks

		$writer = new NBT(NBT::BIG_ENDIAN);
		$nbt->setName("Level");
		$writer->setData(new CompoundTag("", ["Level" => $nbt]));

		return $writer->writeCompressed(ZLIB_ENCODING_DEFLATE, RegionLoader::$COMPRESSION_LEVEL);
	}

	public function nbtDeserialize(string $data){
		$nbt = new NBT(NBT::BIG_ENDIAN);
		try{
			$nbt->readCompressed($data, ZLIB_ENCODING_DEFLATE);

			$chunk = $nbt->getData();

			if(!isset($chunk->Level) or !($chunk->Level instanceof CompoundTag)){
				throw new ChunkException("Invalid NBT format");
			}

			$chunk = $chunk->Level;

			$subChunks = [];
			if($chunk->Sections instanceof ListTag){
				foreach($chunk->Sections as $subChunk){
					if($subChunk instanceof CompoundTag){
						$subChunks[$subChunk->Y->getValue()] = new SubChunk(
							$subChunk->Blocks->getValue(),
							$subChunk->Data->getValue(),
							$subChunk->SkyLight->getValue(),
							$subChunk->BlockLight->getValue()
						);
					}
				}
			}

			$result = new GenericChunk(
				$this,
				$chunk["xPos"],
				$chunk["zPos"],
				$subChunks,
				isset($chunk->Entities) ? $chunk->Entities->getValue() : [],
				isset($chunk->TileEntities) ? $chunk->TileEntities->getValue() : [],
				isset($chunk->Biomes) ? $chunk->Biomes->getValue() : "",
				isset($chunk->HeightMap) ? $chunk->HeightMap->getValue() : []
			);
			$result->setLightPopulated(isset($chunk->LightPopulated) ? ((bool) $chunk->LightPopulated->getValue()) : false);
			$result->setPopulated(isset($chunk->TerrainPopulated) ? ((bool) $chunk->TerrainPopulated->getValue()) : false);
			$result->setGenerated(true);
			return $result;
		}catch(\Throwable $e){
			MainLogger::getLogger()->logException($e);
			return null;
		}
	}

	public static function getProviderName() : string{
		return "pmanvil";
	}
}