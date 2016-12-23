<?php



namespace devmine\levels\generator\object;

use devmine\inventory\blocks\Block;
use devmine\inventory\blocks\Leaves;
use devmine\inventory\blocks\Wood;
use devmine\levels\ChunkManager;
use devmine\utilities\main\Random;

class SpruceTree extends Tree{

	public function __construct(){
		$this->trunkBlock = Block::LOG;
		$this->leafBlock = Block::LEAVES;
		$this->leafType = Leaves::SPRUCE;
		$this->type = Wood::SPRUCE;
		$this->treeHeight = 10;
	}

	public function placeObject(ChunkManager $level, $x, $y, $z, Random $random){
		$this->treeHeight = $random->nextBoundedInt(4) + 6;

		$topSize = $this->treeHeight - (1 + $random->nextBoundedInt(2));
        $lRadius = 2 + $random->nextBoundedInt(2);

		$this->placeTrunk($level, $x, $y, $z, $random, $this->treeHeight - $random->nextBoundedInt(3));

		$radius = $random->nextBoundedInt(2);
		$maxR = 1;
		$minR = 0;

		for($yy = 0; $yy <= $topSize; ++$yy){
			$yyy = $y + $this->treeHeight - $yy;

			for($xx = $x - $radius; $xx <= $x + $radius; ++$xx){
				$xOff = abs($xx - $x);
				for($zz = $z - $radius; $zz <= $z + $radius; ++$zz){
					$zOff = abs($zz - $z);
                    if($xOff === $radius and $zOff === $radius and $radius > 0){
						continue;
					}

					if(!Block::$solid[$level->getBlockIdAt($xx, $yyy, $zz)]){
						$level->setBlockIdAt($xx, $yyy, $zz, $this->leafBlock);
						$level->setBlockDataAt($xx, $yyy, $zz, $this->type);
					}
                }
            }

            if($radius >= $maxR){
				$radius = $minR;
				$minR = 1;
				if(++$maxR > $lRadius){
					$maxR = $lRadius;
				}
			}else{
				++$radius;
			}
		}
	}


}