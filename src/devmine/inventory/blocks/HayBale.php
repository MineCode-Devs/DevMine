<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\creatures\player;

class HayBale extends Solid{

	protected $id = self::HAY_BALE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Hay Bale";
	}

	public function getHardness() {
		return 0.5;
	}

	public function getBurnChance() : int{
		return 60;
	}

	public function getBurnAbility() : int{
		return 20;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$faces = [
			0 => 0,
			1 => 0,
			2 => 0b1000,
			3 => 0b1000,
			4 => 0b0100,
			5 => 0b0100,
		];

		$this->meta = ($this->meta & 0x03) | $faces[$face];
		$this->getLevel()->setBlock($block, $this, true, true);

		return true;
	}

	public function getDrops(Item $item) : array {
		return [
			[$this->id, 0, 1],
		];
	}

}