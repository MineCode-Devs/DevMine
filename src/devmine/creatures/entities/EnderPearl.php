<?php

namespace devmine\creatures\entities;

use devmine\creatures\entities\{Entity, Projectile};
use devmine\worlds\format\Chunk;
use devmine\creatures\player\tag\CompoundTag;
use devmine\server\network\Network;
use devmine\server\network\protocol\AddEntityPacket;
use devmine\creatures\player;

class EnderPearl extends Projectile{
	const NETWORK_ID = 87;

	public $width = 0.25;
	public $length = 0.25;
	public $height = 0.25;

	protected $gravity = 0.03;
	protected $drag = 0.01;
	protected $player;

	public function __construct(Chunk $chunk, CompoundTag $nbt, Entity $shootingEntity = null){
		parent::__construct($chunk, $nbt, $shootingEntity);
	}

	public function onUpdate($currentTick){
		if($this->closed){
			return false;
		}

		$this->timings->startTiming();

		$hasUpdate = parent::onUpdate($currentTick);

		if($this->age > 1200 or $this->isCollided){
			$this->kill();
			$hasUpdate = true;
		}

		$this->timings->stopTiming();

		return $hasUpdate;
	}

	/** @return Player */
	public function getSpawner(){
		return $this->player;
	}

	public function setSpawner(Player $player){
		$this->player = $player;
	}

	public function close(){
		if ($this->getSpawner() instanceof Player) {
			$this->getSpawner()->teleport($this);
		}
		parent::close();
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->type = self::NETWORK_ID;
		$pk->eid = $this->getId();
		$pk->x = $this->x;
		$pk->y = $this->y;
		$pk->z = $this->z;
		$pk->speedX = $this->motionX;
		$pk->speedY = $this->motionY;
		$pk->speedZ = $this->motionZ;
		$pk->metadata = $this->dataProperties;
		$player->dataPacket($pk);

		parent::spawnTo($player);
	}
}
