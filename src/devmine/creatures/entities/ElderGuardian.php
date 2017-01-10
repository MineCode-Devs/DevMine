<?php



namespace devmine\creatures\entities;

use devmine\inventory\items\enchantment\Enchantment;
use devmine\events\entity\EntityDamageByEntityEvent;
use devmine\events\entity\EntityDamageEvent;
use devmine\inventory\items\Item as ItemItem;
use devmine\server\calculations\Vector3;
use devmine\server\network\Network;
use devmine\server\network\protocol\AddEntityPacket;
use devmine\server\network\protocol\EntityEventPacket;
use devmine\creatures\player;
use devmine\server\server;

class ElderGuardian extends WaterAnimal implements Ageable{
	const NETWORK_ID = 50;

	public $width = 1.45;
	public $length = 1.45;
	public $height = 1.8;

	public $dropExp = [5, 10];

	/** @var Vector3 */
	public $swimDirection = null;
	public $swimSpeed = 0.1;

	private $switchDirectionTicker = 0;

	public function initEntity(){
		parent::initEntity();
		$this->setMaxHealth(80);
		$this->setDataFlag(self::DATA_FLAGS, self::DATA_FLAG_ELDER, 1);
	}

	public function getName() : string{
		return "Elder Guardian";
	}

	public function attack($damage, EntityDamageEvent $source){
		parent::attack($damage, $source);
		if($source->isCancelled()){
			return;
		}

		if($source instanceof EntityDamageByEntityEvent){
			$this->swimSpeed = mt_rand(150, 350) / 2000;
			$e = $source->getDamager();
			$this->swimDirection = (new Vector3($this->x - $e->x, $this->y - $e->y, $this->z - $e->z))->normalize();

			$pk = new EntityEventPacket();
			$pk->eid = $this->getId();
			Server::broadcastPacket($this->hasSpawned, $pk);
		}
	}

	private function generateRandomDirection(){
		return new Vector3(mt_rand(-1000, 1000) / 1000, mt_rand(-500, 500) / 1000, mt_rand(-1000, 1000) / 1000);
	}


	public function onUpdate($currentTick){
		if($this->closed !== false){
			return false;
		}

		if(++$this->switchDirectionTicker === 100){
			$this->switchDirectionTicker = 0;
			if(mt_rand(0, 100) < 50){
				$this->swimDirection = null;
			}
		}

		$this->lastUpdate = $currentTick;

		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);

		if($this->isAlive()){

			if($this->y > 62 and $this->swimDirection !== null){
				$this->swimDirection->y = -0.5;
			}

			$inWater = $this->isInsideOfWater();
			if(!$inWater){
				$this->motionY -= $this->gravity;
				$this->swimDirection = null;
			}elseif($this->swimDirection !== null){
				if($this->motionX ** 2 + $this->motionY ** 2 + $this->motionZ ** 2 <= $this->swimDirection->lengthSquared()){
					$this->motionX = $this->swimDirection->x * $this->swimSpeed;
					$this->motionY = $this->swimDirection->y * $this->swimSpeed;
					$this->motionZ = $this->swimDirection->z * $this->swimSpeed;
				}
			}else{
				$this->swimDirection = $this->generateRandomDirection();
				$this->swimSpeed = mt_rand(50, 100) / 2000;
			}

			$expectedPos = new Vector3($this->x + $this->motionX, $this->y + $this->motionY, $this->z + $this->motionZ);

			$this->move($this->motionX, $this->motionY, $this->motionZ);

			if($expectedPos->distanceSquared($this) > 0){
				$this->swimDirection = $this->generateRandomDirection();
				$this->swimSpeed = mt_rand(50, 100) / 2000;
			}

			$friction = 1 - $this->drag;

			$this->motionX *= $friction;
			$this->motionY *= 1 - $this->drag;
			$this->motionZ *= $friction;

			$f = sqrt(($this->motionX ** 2) + ($this->motionZ ** 2));
			$this->yaw = (-atan2($this->motionX, $this->motionZ) * 180 / M_PI);
			$this->pitch = (-atan2($f, $this->motionY) * 180 / M_PI);

			if($this->onGround){
				$this->motionY *= -0.5;
			}

		}

		$this->timings->stopTiming();

		return $hasUpdate or !$this->onGround or abs($this->motionX) > 0.00001 or abs($this->motionY) > 0.00001 or abs($this->motionZ) > 0.00001;
	}


	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->eid = $this->getId();
		$pk->type = ElderGuardian::NETWORK_ID;
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

	public function getDrops(){
		$drops = array(ItemItem::get(ItemItem::PRISMARINE_SHARD, 0, mt_rand(1, 2)));
		$drops[] = ItemItem::get(ItemItem::RAW_FISH, 0, mt_rand(0, 1));
		return $drops;
	}
}
