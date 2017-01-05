<?php



namespace devmine\creatures\entities;


use devmine\server\events\entity\EntityDamageEvent;

use devmine\server\events\entity\ExplosionPrimeEvent;
use devmine\levels\Explosion;
use devmine\levels\format\FullChunk;
use devmine\creatures\player\tag\ByteTag;
use devmine\creatures\player\tag\CompoundTag;
use devmine\server\network\Network;
use devmine\server\network\protocol\AddEntityPacket;
use devmine\Player;

class PrimedTNT extends Entity implements Explosive{
	const NETWORK_ID = 65;

	public $width = 0.98;
	public $length = 0.98;
	public $height = 0.98;

	protected $gravity = 0.04;
	protected $drag = 0.02;

	protected $fuse;

	public $canCollide = false;

	private $dropItem = true;

	public function __construct(FullChunk $chunk, CompoundTag $nbt, bool $dropItem = true){
		parent::__construct($chunk, $nbt);
		$this->dropItem = $dropItem;
	}


	public function attack($damage, EntityDamageEvent $source){
		if($source->getCause() === EntityDamageEvent::CAUSE_VOID){
			parent::attack($damage, $source);
		}
	}

	protected function initEntity(){
		parent::initEntity();

		if(isset($this->namedtag->Fuse)){
			$this->fuse = $this->namedtag["Fuse"];
		}else{
			$this->fuse = 80;
		}
	}


	public function canCollideWith(Entity $entity){
		return false;
	}

	public function saveNBT(){
		parent::saveNBT();
		$this->namedtag->Fuse = new ByteTag("Fuse", $this->fuse);
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

		$hasUpdate = $this->entityBaseTick($tickDiff);

		if($this->isAlive()){

			$this->motionY -= $this->gravity;

			$this->move($this->motionX, $this->motionY, $this->motionZ);

			$friction = 1 - $this->drag;

			$this->motionX *= $friction;
			$this->motionY *= $friction;
			$this->motionZ *= $friction;

			$this->updateMovement();

			if($this->onGround){
				$this->motionY *= -0.5;
				$this->motionX *= 0.7;
				$this->motionZ *= 0.7;
			}

			$this->fuse -= $tickDiff;

			if($this->fuse <= 0){
				$this->kill();
				$this->explode();
			}

		}


		return $hasUpdate or $this->fuse >= 0 or abs($this->motionX) > 0.00001 or abs($this->motionY) > 0.00001 or abs($this->motionZ) > 0.00001;
	}

	public function explode(){
		$this->server->getPluginManager()->callEvent($ev = new ExplosionPrimeEvent($this, 4, $this->dropItem));

		if(!$ev->isCancelled()){
			$explosion = new Explosion($this, $ev->getForce(), $this, $ev->dropItem());
			if($ev->isBlockBreaking()){
				$explosion->explodeA();
			}
			$explosion->explodeB();
		}
	}

	public function spawnTo(Player $player){
		$pk = new AddEntityPacket();
		$pk->type = PrimedTNT::NETWORK_ID;
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