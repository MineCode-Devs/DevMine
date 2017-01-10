<?php



namespace devmine\inventory\blocks;

use devmine\creatures\player;
use devmine\creatures\entities\IronGolem;
use devmine\creatures\entities\SnowGolem;
use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\DoubleTag;
use devmine\creatures\player\tag\FloatTag;
use devmine\creatures\player\tag\ListTag;

class LitPumpkin extends Solid implements SolidLight{

	protected $id = self::LIT_PUMPKIN;

	public function getLightLevel(){
		return 15;
	}

	public function getHardness() {
		return 1;
	}

	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	public function getName() : string{
		return "Jack o'Lantern";
	}

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($player instanceof Player){
			$this->meta = ((int) $player->getDirection() + 5) % 4;
		}
		$this->getLevel()->setBlock($block, $this, true, true);
		if($player != null) {
			$level = $this->getLevel();
			if($player->getServer()->allowSnowGolem) {
				$block0 = $level->getBlock($block->add(0,-1,0));
				$block1 = $level->getBlock($block->add(0,-2,0));
				if($block0->getId() == Item::SNOW_BLOCK and $block1->getId() == Item::SNOW_BLOCK) {
					$level->setBlock($block, new Air());
					$level->setBlock($block0, new Air());
					$level->setBlock($block1, new Air());
					$golem = new SnowGolem($player->getLevel()->getChunk($this->getX() >> 4, $this->getZ() >> 4), new CompoundTag("", [
						"Pos" => new ListTag("Pos", [
							new DoubleTag("", $this->x),
							new DoubleTag("", $this->y),
							new DoubleTag("", $this->z)
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
					]));
					$golem->spawnToAll();
				}
			}
			if($player->getServer()->allowIronGolem) {
				$block0 = $level->getBlock($block->add(0,-1,0));
				$block1 = $level->getBlock($block->add(0,-2,0));
				$block2 = $level->getBlock($block->add(-1,-1,0));
				$block3 = $level->getBlock($block->add(1,-1,0));
				$block4 = $level->getBlock($block->add(0,-1,-1));
				$block5 = $level->getBlock($block->add(0,-1,1));
				if($block0->getId() == Item::IRON_BLOCK and $block1->getId() == Item::IRON_BLOCK) {
					if($block2->getId() == Item::IRON_BLOCK and $block3->getId() == Item::IRON_BLOCK and $block4->getId() == Item::AIR and $block5->getId() == Item::AIR) {
						$level->setBlock($block2, new Air());
						$level->setBlock($block3, new Air());
					}elseif($block4->getId() == Item::IRON_BLOCK and $block5->getId() == Item::IRON_BLOCK and $block2->getId() == Item::AIR and $block3->getId() == Item::AIR){
						$level->setBlock($block4, new Air());
						$level->setBlock($block5, new Air());
					}else return true;
					$level->setBlock($block, new Air());
					$level->setBlock($block0, new Air());
					$level->setBlock($block1, new Air());
					$golem = new IronGolem($player->getLevel()->getChunk($this->getX() >> 4, $this->getZ() >> 4), new CompoundTag("", [
						"Pos" => new ListTag("Pos", [
							new DoubleTag("", $this->x),
							new DoubleTag("", $this->y),
							new DoubleTag("", $this->z)
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
					]));
					$golem->spawnToAll();
				}
			}
		}

		return true;
	}
}
