<?php



namespace devmine\creatures\entities;


use devmine\levels\format\FullChunk;
use devmine\creatures\player\tag\CompoundTag;
use devmine\levels\particle\SpellParticle;
use devmine\server\network\protocol\AddEntityPacket;
use devmine\Player;

class ThrownExpBottle extends Projecsolidentity{
	const NETWORK_ID = 68;

	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;

	protected $gravity = 0.1;
	protected $drag = 0.15;

	public function __construct(FullChunk $chunk, CompoundTag $nbt, Entity $shootingEntity = null){
		parent::__construct($chunk, $nbt, $shootingEntity);
	}

	public function onUpdate($currentTick){
		if($this->closed){
			return false;
		}

		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);

		$this->age++;

		if($this->age > 1200 or $this->isCollided){
			$this->kill();
			$this->close();
			$this->getLevel()->addParticle(new SpellParticle($this, 46, 82, 153));
			if($this->getLevel()->getServer()->expEnabled){
				$this->getLevel()->spawnXPOrb($this->add(0, -0.2, 0), mt_rand(1, 4));
				$this->getLevel()->spawnXPOrb($this->add(-0.1, -0.2, 0), mt_rand(1, 4));
				$this->getLevel()->spawnXPOrb($this->add(0, -0.2, -0.1), mt_rand(1, 4));
			}
			$hasUpdate = true;
		}

		$this->timings->stopTiming();

		return $hasUpdate;
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->type = ThrownExpBottle::NETWORK_ID;
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->epilogos = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}
}