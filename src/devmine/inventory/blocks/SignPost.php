<?php



namespace devmine\inventory\blocks;

use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\worlds\Level;
use devmine\creatures\player;
use devmine\server\calculations\Vector3;

class SignPost extends Transparent{

	protected $id = self::SIGN_POST;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getHardness(){
		return 1;
	}

	public function isSolid(){
		return false;
	}

	public function getName() : string{
		return "Sign Post";
	}

	public function getBoundingBox(){
		return null;
	}


	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if($face !== 0){
			$faces = [
				2 => 2,
				3 => 3,
				4 => 4,
				5 => 5,
			];
			if(!isset($faces[$face])){
				$this->meta = floor((($player->yaw + 180) * 16 / 360) + 0.5) & 0x0F;
				$this->getLevel()->setBlock($block, Block::get(Item::SIGN_POST, $this->meta), true);

				return true;
			}else{
				$this->meta = $faces[$face];
				$this->getLevel()->setBlock($block, Block::get(Item::WALL_SIGN, $this->meta), true);

				return true;
			}
		}

		return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(Vector3::SIDE_DOWN)->getId() === Block::AIR){
				$this->getLevel()->useBreakOn($this);
				
				return Level::BLOCK_UPDATE_NORMAL;
			}
		}
		return false;
	}

	public function getDrops(Item $item) : array{
		return [
			[Item::SIGN, 0, 1],
		];
	}

	public function getToolType(){
		return Tool::TYPE_AXE;
	}
}
