<?php



namespace devmine\creatures\entities;

use devmine\inventory\blocks\Anvil;
use devmine\inventory\blocks\Block;
use devmine\inventory\blocks\Liquid;
use devmine\inventory\blocks\SnowLayer;
use devmine\events\entity\EntityBlockChangeEvent;
use devmine\events\entity\EntityDamageByEntityEvent;
use devmine\events\entity\EntityDamageEvent;

use devmine\inventory\items\Item as ItemItem;
use devmine\worlds\sound\AnvilFallSound;
use devmine\server\calculations\Vector3;
use devmine\creatures\player\tag\ByteTag;
use devmine\creatures\player\tag\IntTag;
use devmine\server\network\protocol\AddEntityPacket;
use devmine\creatures\player;

class FallingSand extends Entity{
	const NETWORK_ID = 66;

	const DATA_BLOCK_INFO = 20;

	public $width = 0.98;
	public $length = 0.98;
	public $height = 0.98;

	protected $gravity = 0.04;
	protected $drag = 0.02;
	protected $blockId = 0;
	protected $damage;

	public $canCollide = false;

	protected function initEntity(){
		parent::initEntity();
		if(isset($this->namedtag->TileID)){
			$this->blockId = $this->namedtag["TileID"];
		}elseif(isset($this->namedtag->Tile)){
			$this->blockId = $this->namedtag["Tile"];
			$this->namedtag["TileID"] = new IntTag("TileID", $this->blockId);
		}

		if(isset($this->namedtag->Data)){
			$this->damage = $this->namedtag["Data"];
		}

		if($this->blockId === 0){
			$this->close();
			return;
		}

		$this->setDataProperty(self::DATA_BLOCK_INFO, self::DATA_TYPE_INT, $this->getBlock() | ($this->getDamage() << 8));
	}

	public function canCollideWith(Entity $entity){
		return false;
	}

	public function attack($damage, EntityDamageEvent $source){
		if($source->getCause() === EntityDamageEvent::CAUSE_VOID){
			parent::attack($damage, $source);
		}
	}

	public function onUpdate($currentTick){

		if($this->closed){
			return false;
		}

		$this->timings->startTiming();

		$tickDiff = $currentTick - $this->lastUpdate;
		if($tickDiff <= 0 and !$this->justCreated){
			return true;
		}

		$this->lastUpdate = $currentTick;

		$height = $this->fallDistance;

		$hasUpdate = $this->entityBaseTick($tickDiff);

		if($this->isAlive()){
			$pos = (new Vector3($this->x - 0.5, $this->y, $this->z - 0.5))->round();

			if($this->ticksLived === 1){
				$block = $this->level->getBlock($pos);
				if($block->getId() !== $this->blockId){
					return true;
				}
				$this->level->setBlock($pos, Block::get(0), true);
			}

			$this->motionY -= $this->gravity;

			$this->move($this->motionX, $this->motionY, $this->motionZ);

			$friction = 1 - $this->drag;

			$this->motionX *= $friction;
			$this->motionY *= 1 - $this->drag;
			$this->motionZ *= $friction;

			$pos = (new Vector3($this->x - 0.5, $this->y, $this->z - 0.5))->round();

			if($this->onGround){
				$this->kill();
				$block = $this->level->getBlock($pos);
				if($block->getId() > 0 and !$block->isSolid() and !($block instanceof Liquid)){
					$this->getLevel()->dropItem($this, ItemItem::get($this->getBlock(), $this->getDamage(), 1));
				}else{
					if($block instanceof SnowLayer){
						$oldDamage = $block->getDamage();
						$this->server->getPluginManager()->callEvent($ev = new EntityBlockChangeEvent($this, $block, Block::get($this->getBlock(), $this->getDamage() + $oldDamage)));
					}else{
						$this->server->getPluginManager()->callEvent($ev = new EntityBlockChangeEvent($this, $block, Block::get($this->getBlock(), $this->getDamage())));
					}

					if(!$ev->isCancelled()){
						$this->getLevel()->setBlock($pos, $ev->getTo(), true);
						if($ev->getTo() instanceof Anvil){
							$sound = new AnvilFallSound($this);
							$this->getLevel()->addSound($sound);
							foreach($this->level->getNearbyEntities($this->boundingBox->grow(0.1, 0.1, 0.1), $this) as $entity){
								$entity->scheduleUpdate();
								if(!$entity->isAlive()){
									continue;
								}
								if($entity instanceof Living){
									$damage = ($height - 1) * 2;
									if($damage > 40) $damage = 40;
									$ev = new EntityDamageByEntityEvent($this, $entity, EntityDamageByEntityEvent::CAUSE_FALL, $damage, 0.1);
									$entity->attack($damage, $ev);
								}
							}

						}
					}
				}
				$hasUpdate = true;
			}

			$this->updateMovement();
		}

		return $hasUpdate or !$this->onGround or abs($this->motionX) > 0.00001 or abs($this->motionY) > 0.00001 or abs($this->motionZ) > 0.00001;
	}

	public function getBlock(){
		return $this->blockId;
	}

	public function getDamage(){
		return $this->damage;
	}

	public function saveNBT(){
		$this->namedtag->TileID = new IntTag("TileID", $this->blockId);
		$this->namedtag->Data = new ByteTag("Data", $this->damage);
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->type = FallingSand::NETWORK_ID;
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->yaw = $this->yaw;
		$pk->pitch = $this->pitch;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}
}
