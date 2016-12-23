<?php



namespace devmine\inventory\blocks;

use devmine\creatures\entities\Entity;
use devmine\inventory\items\Item;
use devmine\Player;

class Water extends Liquid{

	protected $id = self::WATER;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Water";
	}

	public function onEntityCollide(Entity $entity){
		$entity->resetFallDistance();
		if($entity->fireTicks > 0){
			$entity->extinguish();
		}

		$entity->resetFallDistance();
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$ret = $this->getLevel()->setBlock($this, $this, true, false);
		$this->getLevel()->scheduleUpdate($this, $this->tickRate());

		return $ret;
	}
}
