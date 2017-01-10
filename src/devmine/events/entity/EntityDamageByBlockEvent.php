<?php



namespace devmine\events\entity;

use devmine\inventory\blocks\Block;
use devmine\creatures\entities\Entity;

class EntityDamageByBlockEvent extends EntityDamageEvent{

	/** @var Block */
	private $damager;


	/**
	 * @param Block     $damager
	 * @param Entity    $entity
	 * @param int       $cause
	 * @param int|int[] $damage
	 */
	public function __construct(Block $damager, Entity $entity, $cause, $damage){
		$this->damager = $damager;
		parent::__construct($entity, $cause, $damage);
	}

	/**
	 * @return Block
	 */
	public function getDamager(){
		return $this->damager;
	}


}