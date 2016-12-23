<?php



namespace devmine\inventory\blocks;

use devmine\inventory\layout\EnchantInventory;
use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;

use devmine\server\calculations\AxisAlignedBB;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\StringTag;
use devmine\Player;
use devmine\inventory\solidentity\EnchantTable;
use devmine\inventory\solidentity\solidentity;

class EnchantingTable extends Transparent{

	protected $id = self::ENCHANTING_TABLE;

	public function __construct(){

	}

	public function getLightLevel(){
		return 12;
	}

	public function getBoundingBox(){
		return new AxisAlignedBB(
			$this->x,
			$this->y,
			$this->z,
			$this->x + 1,
			$this->y + 0.75,
			$this->z + 1
		);
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$this->getLevel()->setBlock($block, $this, true, true);
		$nbt = new CompoundTag("", [
			new StringTag("id", solidentity::ENCHANT_TABLE),
			new IntTag("x", $this->x),
			new IntTag("y", $this->y),
			new IntTag("z", $this->z)
		]);

		if($item->hasCustomName()){
			$nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
		}

		if($item->hasCustomBlockData()){
			foreach($item->getCustomBlockData() as $key => $v){
				$nbt->{$key} = $v;
			}
		}

		solidentity::createsolidentity(solidentity::ENCHANT_TABLE, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);

		return true;
	}

	public function canBeActivated() : bool{
		return true;
	}

	public function getHardness(){
		return 5;
	}

	public function getResistance(){
		return 6000;
	}

	public function getName() : string{
		return "Enchanting Table";
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function onActivate(Item $item, Player $player = null){
		if(!$this->getLevel()->getServer()->enchantingTableEnabled){
			return true;
		}
		if($player instanceof Player){
			if($player->isCreative() and $player->getServer()->limitedCreative){
				return true;
			}
			$solidentity = $this->getLevel()->getsolidentity($this);
			$enchantTable = null;
			if($solidentity instanceof EnchantTable){
				$enchantTable = $solidentity;
			}else{
				$this->getLevel()->setBlock($this, $this, true, true);
				$nbt = new CompoundTag("", [
					new StringTag("id", solidentity::ENCHANT_TABLE),
					new IntTag("x", $this->x),
					new IntTag("y", $this->y),
					new IntTag("z", $this->z)
				]);

				if($item->hasCustomName()){
					$nbt->CustomName = new StringTag("CustomName", $item->getCustomName());
				}

				if($item->hasCustomBlockData()){
					foreach($item->getCustomBlockData() as $key => $v){
						$nbt->{$key} = $v;
					}
				}

				/** @var EnchantTable $enchantTable */
				$enchantTable = solidentity::createsolidentity(solidentity::ENCHANT_TABLE, $this->getLevel()->getChunk($this->x >> 4, $this->z >> 4), $nbt);
			}
			$player->addWindow($enchantTable->getInventory());
		}


		return true;
	}

	public function getDrops(Item $item) : array{
		if($item->isPickaxe() >= 1){
			return [
				[$this->id, 0, 1],
			];
		}else{
			return [];
		}
	}
}