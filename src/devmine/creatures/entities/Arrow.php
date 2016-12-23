<?php



namespace devmine\creatures\entities;

use devmine\inventory\items\Potion;
use devmine\levels\format\FullChunk;
use devmine\levels\particle\CriticalParticle;
use devmine\levels\particle\MobSpellParticle;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\ShortTag;
use devmine\server\network\protocol\AddEntityPacket;
use devmine\Player;

class Arrow extends Projecsolidentity{
	const NETWORK_ID = 80;

	public $width = 0.5;
	public $length = 0.5;
	public $height = 0.5;

	protected $gravity = 0.05;
	protected $drag = 0.01;

	protected $damage = 2;

	protected $isCritical;
	protected $potionId;

	public function __construct(FullChunk $chunk, CompoundTag $nbt, Entity $shootingEntity = null, $critical = false){
		$this->isCritical = (bool) $critical;
		if(!isset($nbt->Potion)){
			$nbt->Potion = new ShortTag("Potion", 0);
		}
		parent::__construct($chunk, $nbt, $shootingEntity);
		$this->potionId = $this->namedtag["Potion"];
	}

	public function getPotionId() : int{
		return $this->potionId;
	}

	public function onUpdate($currentTick){
		if($this->closed){
			return false;
		}

		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);

		if(!$this->hadCollision and $this->isCritical){
			$this->level->addParticle(new CriticalParticle($this->add(
				$this->width / 2 + mt_rand(-100, 100) / 500,
				$this->height / 2 + mt_rand(-100, 100) / 500,
				$this->width / 2 + mt_rand(-100, 100) / 500)));
		}elseif($this->onGround){
			$this->isCritical = false;
		}

		if($this->potionId != 0){
			if(!$this->onGround or ($this->onGround and ($currentTick % 4) == 0)){
				$color = Potion::getColor($this->potionId - 1);
				$this->level->addParticle(new MobSpellParticle($this->add(
					$this->width / 2 + mt_rand(-100, 100) / 500,
					$this->height / 2 + mt_rand(-100, 100) / 500,
					$this->width / 2 + mt_rand(-100, 100) / 500), $color[0], $color[1], $color[2]));
			}
			$hasUpdate = true;
		}

		if($this->age > 1200){
			$this->kill();
			$hasUpdate = true;
		}

		$this->timings->stopTiming();

		return $hasUpdate;
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->type = Arrow::NETWORK_ID;
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