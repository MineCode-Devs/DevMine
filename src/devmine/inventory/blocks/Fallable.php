<?php



namespace devmine\inventory\blocks;

use devmine\creatures\entities\Entity;
use devmine\inventory\items\Item;
use devmine\levels\Level;
use devmine\server\calculations\Vector3;
use devmine\creatures\player\tag\ByteTag;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\DoubleTag;
use devmine\creatures\player\tag\ListTag;
use devmine\creatures\player\tag\FloatTag;
use devmine\creatures\player\tag\IntTag;
use devmine\Player;

abstract class Fallable extends Solid{

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$ret = $this->getLevel()->setBlock($this, $this, true, true);

		return $ret;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			$down = $this->getSide(Vector3::SIDE_DOWN);
			if($down->getId() === self::AIR or ($down instanceof Liquid)){
				$fall = Entity::createEntity("FallingSand", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), new CompoundTag("", [
					"Pos" => new ListTag("Pos", [
						new DoubleTag("", $this->x + 0.5),
						new DoubleTag("", $this->y),
						new DoubleTag("", $this->z + 0.5)
					]),
					"Motion" => new ListTag("Motion", [
						new DoubleTag("", 0),
						new DoubleTag("", 0),
						new DoubleTag("", 0)
					]),
					"Rotation" => new ListTag("Rotation", [
						new FloatTag("", 0),
						new FloatTag("", 0)
					]),
					"solidentityID" => new IntTag("solidentityID", $this->getId()),
					"Data" => new ByteTag("Data", $this->getDamage()),
				]));

				$fall->spawnToAll();
			}
		}
	}
}