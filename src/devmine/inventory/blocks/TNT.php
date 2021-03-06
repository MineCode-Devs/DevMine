<?php



namespace devmine\inventory\blocks;

use devmine\creatures\entities\Entity;
use devmine\inventory\items\Item;
use devmine\worlds\Level;
use devmine\worlds\sound\TNTPrimeSound;
use devmine\server\calculations\Vector3;
use devmine\creatures\player\tag\ByteTag;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\DoubleTag;
use devmine\creatures\player\tag\ListTag;
use devmine\creatures\player\tag\FloatTag;
use devmine\creatures\player;
use devmine\utilities\main\Random;

class TNT extends Solid implements ElectricalAppliance{

	protected $id = self::TNT;

	public function __construct(){

	}

	public function getName() : string{
		return "TNT";
	}

	public function getHardness(){
		return 0;
	}

	public function canBeActivated() : bool{
		return true;
	}

	public function getBurnChance() : int{
		return 15;
	}

	public function getBurnAbility() : int{
		return 100;
	}

	public function prime(Player $player = null){
		$this->meta = 1;
		if($player != null and $player->isCreative()){
			$dropItem = false;
		}else{
			$dropItem = true;
		}
		$mot = (new Random())->nextSignedFloat() * M_PI * 2;
		$tnt = Entity::createEntity("PrimedTNT", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), new CompoundTag("", [
			"Pos" => new ListTag("Pos", [
				new DoubleTag("", $this->x + 0.5),
				new DoubleTag("", $this->y),
				new DoubleTag("", $this->z + 0.5)
			]),
			"Motion" => new ListTag("Motion", [
				new DoubleTag("", -sin($mot) * 0.02),
				new DoubleTag("", 0.2),
				new DoubleTag("", -cos($mot) * 0.02)
			]),
			"Rotation" => new ListTag("Rotation", [
				new FloatTag("", 0),
				new FloatTag("", 0)
			]),
			"Fuse" => new ByteTag("Fuse", 80)
		]), $dropItem);

		$tnt->spawnToAll();
		$this->level->addSound(new TNTPrimeSound($this));
	}

	public function onUpdate($type){
		if($type == Level::BLOCK_UPDATE_SCHEDULED){
			$sides = [0, 1, 2, 3, 4, 5];
			foreach($sides as $side){
				$block = $this->getSide($side);
				if($block instanceof RedstoneSource and $block->isActivated($this)){
					$this->prime();
					$this->getLevel()->setBlock($this, new Air(), true);
					break;
				}
			}
			return Level::BLOCK_UPDATE_SCHEDULED;
		}
		return false;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$this->getLevel()->setBlock($this, $this, true, false);

		$this->getLevel()->scheduleUpdate($this, 40);
	}

	public function onActivate(Item $item, Player $player = null){
		if($item->getId() === Item::FLINT_STEEL){
			$this->prime($player);
			$this->getLevel()->setBlock($this, new Air(), true);
			$item->useOn($this);
			return true;
		}

		return false;
	}
}
