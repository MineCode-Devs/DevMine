<?php



namespace devmine\server\events\entity;

use devmine\creatures\entities\Creeper;
use devmine\server\events\Cancellable;
use devmine\creatures\entities\Lightning;

class CreeperPowerEvent extends EntityEvent implements Cancellable{
	public static $handlerList = null;

	const CAUSE_SET_ON = 0;
	const CAUSE_SET_OFF = 1;
	const CAUSE_LIGHTNING = 2;

	/** @var  Lightning */
	private $lightning;

	private $cause;

	public function __construct(Creeper $creeper, Lightning $lightning = null, int $cause = self::CAUSE_LIGHTNING){
		$this->entity = $creeper;
		$this->lightning = $lightning;
		$this->cause = $cause;
	}

	public function getLightning(){
		return $this->lightning;
	}

	public function getCause(){
		return $this->cause;
	}
}
