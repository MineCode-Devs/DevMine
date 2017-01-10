<?php



namespace devmine\inventory\blocks;

use devmine\inventory\blocks\Block;
use devmine\inventory\blocks\Solid;
use devmine\inventory\items\Item;
use devmine\creatures\player;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\ByteTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\StringTag;
use devmine\inventory\solidentity\Tile;
use devmine\server\calculations\Vector3;

 class BeaconBlock extends Solid{
 
 	protected $id = self::BEACON_BLOCK;
 
 	public function __construct($meta = 0){
 		$this->meta = $meta;
 	}
 
 	public function canBeActivated() : bool{
 		return false;
 	}
 
 	public function getName(){
 		return "Beacon";
 	}
 
 	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
 		$this->getLevel()->setBlock($this, $this, true, true);
 		$nbt = new CompoundTag("", [
 			new StringTag("id", Tile::BEACON),
 			new IntTag("x", $block->x),
 			new IntTag("y", $block->y),
 			new IntTag("z", $block->z)
 		]);
 		$pot = Tile::createTile(Tile::BEACON, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
 		return true;
 	}
 }
