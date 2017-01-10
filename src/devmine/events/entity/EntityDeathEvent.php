<?php



namespace devmine\events\entity;

use devmine\creatures\entities\Living;
use devmine\inventory\items\Item;

class EntityDeathEvent extends EntityEvent{
	public static $handlerList = null;

	/** @var Item[] */
	private $drops = [];


	/**
	 * @param Living $entity
	 * @param Item[] $drops
	 */
	public function __construct(Living $entity, array $drops = []){
		$this->entity = $entity;
		$this->drops = $drops;
	}

	/**
	 * @return Living
	 */
	public function getEntity(){
		return $this->entity;
	}

	/**
	 * @return \devmine\inventory\items\Item[]
	 */
	public function getDrops(){
		return $this->drops;
	}

	/**
	 * @param Item[] $drops
	 */
	public function setDrops(array $drops){
		$this->drops = $drops;
	}

}