<?php



namespace devmine\events\entity;

use devmine\creatures\entities\Entity;

class EntityCombustByEntityEvent extends EntityCombustEvent{

	protected $combuster;

	/**
	 * @param Entity $combuster
	 * @param Entity $combustee
	 * @param int    $duration
	 * @param int    $ProtectLevel
	 */
	public function __construct(Entity $combuster, Entity $combustee, $duration, $ProtectLevel = 0){
		parent::__construct($combustee, $duration, $ProtectLevel);
		$this->combuster = $combuster;
	}

	/**
	 * @return Entity
	 */
	public function getCombuster(){
		return $this->combuster;
	}

}