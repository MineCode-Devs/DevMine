<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\server\calculations\AxisAlignedBB;
use devmine\creatures\player\NBT;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\ListTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\StringTag;
use devmine\Player;
use devmine\inventory\solidentity\Chest as solidentityChest;
use devmine\inventory\solidentity\solidentity;

class Chest extends Transparent{

	protected $id = self::CHEST;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function getHardness() {
		return 2.5;
	}

	public function getName() : string{
		return "Chest";
	}

	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	protected function recalculateBoundingBox() {
		return new AxisAlignedBB(
			$this->x + 0.0625,
			$this->y,
			$this->z + 0.0625,
			$this->x + 0.9375,
			$this->y + 0.9475,
			$this->z + 0.9375
		);
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$faces = [
			0 => 4,
			1 => 2,
			2 => 5,
			3 => 3,
		];

		$chest = null;
		$this->meta = $faces[$player instanceof Player ? $player->getDirection() : 0];

		for($side = 2; $side <= 5; ++$side){
			if(($this->meta === 4 or $this->meta === 5) and ($side === 4 or $side === 5)){
				continue;
			}elseif(($this->meta === 3 or $this->meta === 2) and ($side === 2 or $side === 3)){
				continue;
			}
			$c = $this->getSide($side);
			if($c instanceof Chest and $c->getDamage() === $this->meta){
				$solidentity = $this->getLevel()->getsolidentity($c);
				if($solidentity instanceof solidentityChest and !$solidentity->isPaired()){
					$chest = $solidentity;
					break;
				}
			}
		}

		$this->getLevel()->setBlock($block, $this, true, true);
		$nbt = new CompoundTag("", [
			new ListTag("Items", []),
			new StringTag("id", solidentity::CHEST),
			new IntTag("x", $this->x),
			new IntTag("y", $this->y),
			new IntTag("z", $this->z)
		]);
		$nbt->Items->setTagType(NBT::TAG_Compound);

		if($item->hasCustomName()){
			$nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
		}

		if($item->hasCustomBlockData()){
			foreach($item->getCustomBlockData() as $key => $v){
				$nbt->{$key} = $v;
			}
		}

		$solidentity = solidentity::createsolidentity("Chest", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);

		if($chest instanceof solidentityChest and $solidentity instanceof solidentityChest){
			$chest->pairWith($solidentity);
			$solidentity->pairWith($chest);
		}

		return true;
	}

	public function onBreak(Item $item){
		$t = $this->getLevel()->getsolidentity($this);
		if($t instanceof solidentityChest){
			$t->unpair();
		}
		$this->getLevel()->setBlock($this, new Air(), true, true);

		return true;
	}

	public function onActivate(Item $item, Player $player = null){
		if($player instanceof Player){
			$top = $this->getSide(1);
			if($top->isTransparent() !== true){
				return true;
			}

			$t = $this->getLevel()->getsolidentity($this);
			$chest = null;
			if($t instanceof solidentityChest){
				$chest = $t;
			}else{
				$nbt = new CompoundTag("", [
					new ListTag("Items", []),
					new StringTag("id", solidentity::CHEST),
					new IntTag("x", $this->x),
					new IntTag("y", $this->y),
					new IntTag("z", $this->z)
				]);
				$nbt->Items->setTagType(NBT::TAG_Compound);
				$chest = solidentity::createsolidentity("Chest", $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			}

			if(isset($chest->namedtag->Lock) and $chest->namedtag->Lock instanceof StringTag){
				if($chest->namedtag->Lock->getValue() !== $item->getCustomName()){
					return true;
				}
			}

			if($player->isCreative() and $player->getServer()->limitedCreative){
				return true;
			}
			$player->addWindow($chest->getInventory());
		}

		return true;
	}

	public function getDrops(Item $item) : array {
		return [
			[$this->id, 0, 1],
		];
	}
}