<?php



namespace devmine\events\entity;

use devmine\creatures\entities\Entity;
use devmine\events\Cancellable;

class EntityCombustEvent extends EntityEvent implements Cancellable{
	public static $handlerList = null;

	protected $duration;
	protected $ProtectLevel;

	/**
	 * @param Entity $combustee
	 * @param int    $duration
	 * @param int    $ProtectLevel
	 */
	public function __construct(Entity $combustee, $duration, $ProtectLevel = 0){
		$this->entity = $combustee;
		$this->duration = $duration;
		$this->ProtectLevel = $ProtectLevel;
	}

	public function getDuration(){
		if($this->ProtectLevel !== 0){
			return round($this->duration * (1 - 0.15 * $this->ProtectLevel));
		}else{
			return $this->duration;
		}
	}

	public function setDuration($duration){
		$this->duration = (int) $duration;
	}

	public function setProtectLevel($ProtectLevel){
		$this->ProtectLevel = (int) $ProtectLevel;
	}
}