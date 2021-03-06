<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\worlds\Level;
use devmine\server\calculations\AxisAlignedBB;
use devmine\server\calculations\Vector3;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\ShortTag;
use devmine\creatures\player\tag\StringTag;
use devmine\creatures\player;
use devmine\inventory\solidentity\FlowerPot as TileFlowerPot;
use devmine\inventory\solidentity\Tile;

class FlowerPot extends Flowable{

	const STATE_EMPTY = 0;
	const STATE_FULL = 1;

	protected $id = self::FLOWER_POT_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Flower Pot Block";
	}

	public function canBeActivated(): bool{
		return true;
	}

	protected function recalculateBoundingBox(){
		return new AxisAlignedBB(
			$this->x + 0.3125,
			$this->y,
			$this->z + 0.3125,
			$this->x + 0.6875,
			$this->y + 0.375,
			$this->z + 0.6875
		);
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($this->getSide(Vector3::SIDE_DOWN)->isTransparent()){
			return false;
		}

		$this->getLevel()->setBlock($block, $this, true, true);

		$nbt = new CompoundTag("", [
			new StringTag("id", Tile::FLOWER_POT),
			new IntTag("x", $block->x),
			new IntTag("y", $block->y),
			new IntTag("z", $block->z),
			new ShortTag("item", 0),
			new IntTag("mData", 0),
		]);

		if($item->hasCustomBlockData()){
			foreach($item->getCustomBlockData() as $key => $v){
				$nbt->{$key} = $v;
			}
		}

		Tile::createTile(Tile::FLOWER_POT, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
		return true;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->isTransparent() === true){
				$this->getLevel()->useBreakOn($this);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}

	public function onActivate(Item $item, Player $player = null){
		$pot = $this->getLevel()->getTile($this);
		if(!($pot instanceof TileFlowerPot)){
			return false;
		}
		if(!$pot->canAddItem($item)){
			return true;
		}

		$this->setDamage(self::STATE_FULL); //specific damage value is unnecessary, it just needs to be non-zero to show an item.
		$this->getLevel()->setBlock($this, $this, true, false);
		$pot->setItem($item);

		if($player instanceof Player){
			if($player->isSurvival()){
				$item->setCount($item->getCount() - 1);
				$player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
			}
		}
		return true;
	}

	public function getDrops(Item $item) : array{
		$items = [[Item::FLOWER_POT, 0, 1]];
		$tile = $this->getLevel()->getTile($this);
		if($tile instanceof TileFlowerPot){
			if(($item = $tile->getItem())->getId() !== Item::AIR){
				$items[] = [$item->getId(), $item->getDamage(), 1];
			}
		}
		return $items;
	}

}
