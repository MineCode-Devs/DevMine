<?php



namespace devmine\levels\generator\populator;

use devmine\levels\ChunkManager;
use devmine\levels\generator\object\Ore as ObjectOre;
use devmine\utilities\main\Random;

class Ore extends Populator{
	private $oreTypes = [];

	public function populate(ChunkManager $level, $chunkX, $chunkZ, Random $random){
		foreach($this->oreTypes as $type){
			$ore = new ObjectOre($random, $type);
			for($i = 0; $i < $ore->type->clusterCount; ++$i){
				$x = $random->nextRange($chunkX << 4, ($chunkX << 4) + 15);
				$y = $random->nextRange($ore->type->minHeight, $ore->type->maxHeight);
				$z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);
				if($ore->canPlaceObject($level, $x, $y, $z)){
					$ore->placeObject($level, $x, $y, $z);
				}
			}
		}
	}

	public function setOreTypes(array $types){
		$this->oreTypes = $types;
	}
}