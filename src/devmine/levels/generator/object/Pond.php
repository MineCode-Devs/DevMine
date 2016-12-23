<?php



namespace devmine\levels\generator\object;

use devmine\inventory\blocks\Block;
use devmine\levels\ChunkManager;
use devmine\server\calculations\Vector3 as Vector3;
use devmine\utilities\main\Random;

class Pond{
	private $random;
	public $type;

	public function __construct(Random $random, Block $type){
		$this->type = $type;
		$this->random = $random;
	}

	public function canPlaceObject(ChunkManager $level, Vector3 $pos){
	}

	public function placeObject(ChunkManager $level, Vector3 $pos){
	}

}