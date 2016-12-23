<?php



namespace devmine\inventory\blocks;


use devmine\inventory\items\Tool;

class Planks extends Solid{
	const OAK = 0;
	const SPRUCE = 1;
	const BIRCH = 2;
	const JUNGLE = 3;
	const ACACIA = 4;
	const DARK_OAK = 5;

	protected $id = self::WOODEN_PLANKS;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness() {
		return 2;
	}

	public function getToolType(){
		return Tool::TYPE_AXE;
	}

	public function getBurnChance() : int{
		return 5;
	}

	public function getBurnAbility() : int{
		return 20;
	}

	public function getName() : string{
		static $names = [
			self::OAK => "Oak Wood Planks",
			self::SPRUCE => "Spruce Wood Planks",
			self::BIRCH => "Birch Wood Planks",
			self::JUNGLE => "Jungle Wood Planks",
			self::ACACIA => "Acacia Wood Planks",
			self::DARK_OAK => "Jungle Wood Planks",
			"",
			""
		];
		return $names[$this->meta & 0x07];
	}

}