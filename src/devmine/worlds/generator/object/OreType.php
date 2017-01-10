<?php



namespace devmine\worlds\generator\object;

use devmine\inventory\blocks\Block;

class OreType{
	public $material, $clusterCount, $clusterSize, $maxHeight, $minHeight;

	public function __construct(Block $material, $clusterCount, $clusterSize, $minHeight, $maxHeight){
		$this->material = $material;
		$this->clusterCount = (int) $clusterCount;
		$this->clusterSize = (int) $clusterSize;
		$this->maxHeight = (int) $maxHeight;
		$this->minHeight = (int) $minHeight;
	}
}