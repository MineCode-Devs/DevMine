<?php



namespace devmine\inventory\blocks;

use devmine\inventory\layout\AnvilInventory;
use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\levels\sound\AnvilFallSound;
use devmine\Player;

class Anvil extends Fallable{
	
	const NORMAL = 0;
	const SLIGHTLY_DAMAGED = 4;
	const VERY_DAMAGED = 8;
	
	protected $id = self::ANVIL;

	public function isSolid(){
		return false;
	}

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function getHardness() {
		return 5;
	}

	public function getResistance(){
		return 6000;
	}

	public function getName() : string{
		return "Anvil";
	}

	public function getToolType(){
		return Tool::TYPE_PICKAXE;
	}

	public function onActivate(Item $item, Player $player = null){
		if(!$this->getLevel()->getServer()->anvilEnabled){
			return true;
		}
		if($player instanceof Player){
			if($player->isCreative() and $player->getServer()->limitedCreative){
				return true;
			}

			$player->addWindow(new AnvilInventory($this));
		}

		return true;
	}
	
	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		parent::place($item, $block, $target, $face, $fx, $fy, $fz, $player);
		$this->getLevel()->addSound(new AnvilFallSound($this));
	}

	public function getDrops(Item $item) : array {
		if($item->isPickaxe() >= 1){
			return [
				[$this->id, 0, 1], //TODO break level
			];
		}else{
			return [];
		}
	}
}