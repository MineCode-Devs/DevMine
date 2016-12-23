<?php



namespace devmine\inventory\blocks;

use devmine\creatures\entities\Entity;
use devmine\server\events\entity\EntityEatBlockEvent;
use devmine\inventory\items\FoodSource;
use devmine\inventory\items\Item;
use devmine\levels\Level;
use devmine\server\calculations\AxisAlignedBB;
use devmine\Player;


class Cake extends Transparent implements FoodSource{

	protected $id = self::CAKE_BLOCK;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated() : bool {
		return true;
	}

	public function getHardness() {
		return 0.5;
	}

	public function getName() : string{
		return "Cake Block";
	}

	protected function recalculateBoundingBox() {

		$f = (1 + $this->getDamage() * 2) / 16;

		return new AxisAlignedBB(
			$this->x + $f,
			$this->y,
			$this->z + 0.0625,
			$this->x + 1 - 0.0625,
			$this->y + 0.5,
			$this->z + 1 - 0.0625
		);
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$down = $this->getSide(0);
		if($down->getId() !== self::AIR){
			$this->getLevel()->setBlock($block, $this, true, true);

			return true;
		}

		return false;
	}

	public function onUpdate($type){
		if($type === Level::BLOCK_UPDATE_NORMAL){
			if($this->getSide(0)->getId() === self::AIR){ //Replace with common break method
				$this->getLevel()->setBlock($this, new Air(), true);

				return Level::BLOCK_UPDATE_NORMAL;
			}
		}

		return false;
	}

	public function getDrops(Item $item) : array {
		return [];
	}

	public function canBeConsumed() : bool{
		return true;
	}

	public function canBeConsumedBy(Entity $entity) : bool{
		return $entity instanceof Player and ($entity->getFood() < $entity->getMaxFood()) and $this->canBeConsumed();
	}

	public function getResidue(){
		$new = clone $this;
		return $new;
	}

	public function getAdditionalEffects() : array{
		return [];
	}

	public function getFoodRestore() : int{
		return 2;
	}

	public function getSaturationRestore() : float{
		return 0.4;
	}

	public function onActivate(Item $item, Player $player = null){
		if($player instanceof Player and $player->getFood() < 20){
			$player->getServer()->getPluginManager()->callEvent($ev = new EntityEatBlockEvent($player, $this));
			if(!$ev->isCancelled()){
				$player->setFood($player->getFood() + 2);
				++$this->meta;

				if($this->meta >= 0x06){
					$this->getLevel()->setBlock($this, new Air(), true);
				}else{
					$this->getLevel()->setBlock($this, $this, true);
				}

				return true;
			}
		}

		return false;
	}

}