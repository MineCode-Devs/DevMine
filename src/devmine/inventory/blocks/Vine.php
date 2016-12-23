<?php



namespace devmine\inventory\blocks;

use devmine\creatures\entities\Entity;
use devmine\inventory\items\Item;
use devmine\inventory\items\Tool;
use devmine\levels\Level;
use devmine\server\calculations\AxisAlignedBB;
use devmine\Player;

class Vine extends Transparent{

	protected $id = self::VINE;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function isSolid(){
		return false;
	}

	public function getName() : string{
		return "Vines";
	}

	public function getHardness() {
		return 0.2;
	}

	public function canPassThrough(){
		return true;
	}

	public function hasEntityCollision(){
		return true;
	}

	public function onEntityCollide(Entity $entity){
		$entity->resetFallDistance();
	}

	protected function recalculateBoundingBox() {

		$f1 = 1;
		$f2 = 1;
		$f3 = 1;
		$f4 = 0;
		$f5 = 0;
		$f6 = 0;

		$flag = $this->meta > 0;

		if(($this->meta & 0x02) > 0){
			$f4 = max($f4, 0.0625);
			$f1 = 0;
			$f2 = 0;
			$f5 = 1;
			$f3 = 0;
			$f6 = 1;
			$flag = true;
		}

		if(($this->meta & 0x08) > 0){
			$f1 = min($f1, 0.9375);
			$f4 = 1;
			$f2 = 0;
			$f5 = 1;
			$f3 = 0;
			$f6 = 1;
			$flag = true;
		}

		if(($this->meta & 0x01) > 0){
			$f3 = min($f3, 0.9375);
			$f6 = 1;
			$f1 = 0;
			$f4 = 1;
			$f2 = 0;
			$f5 = 1;
			$flag = true;
		}

		if(!$flag and $this->getSide(1)->isSolid()){
			$f2 = min($f2, 0.9375);
			$f5 = 1;
			$f1 = 0;
			$f4 = 1;
			$f3 = 0;
			$f6 = 1;
		}

		return new AxisAlignedBB(
			$this->x + $f1,
			$this->y + $f2,
			$this->z + $f3,
			$this->x + $f4,
			$this->y + $f5,
			$this->z + $f6
		);
	}


	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		if(!$target->isTransparent() and $target->isSolid()){
			$faces = [
				0 => 0,
				1 => 0,
				2 => 1,
				3 => 4,
				4 => 8,
				5 => 2,
			];
			if(isset($faces[$face])){
				$this->meta = $faces[$face];
				$this->getLevel()->setBlock($block, $this, true, true);

				return true;
			}
		}

		return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			/*if($this->getSide(0)->getId() === self::AIR){ //Replace with common break method
				Server::getInstance()->api->entity->drop($this, Item::get(LADDER, 0, 1));
				$this->getLevel()->setBlock($this, new Air(), true, true, true);
				return Level::BLOCK_UPDATE_NORMAL;
			}*/
		}

		return false;
	}

	public function getDrops(Item $item) : array {
		if($item->isShears()){
			return [
				[$this->id, 0, 1],
			];
		}else{
			return [];
		}
	}

	public function getToolType(){
		return Tool::TYPE_SHEARS;
	}
}