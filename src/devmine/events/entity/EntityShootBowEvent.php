<?php

/**
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author devmine Team
 * @link   http://www.devmine.net/
 *
 *
 */

namespace devmine\server\events\entity;

use devmine\creatures\entities\Entity;
use devmine\creatures\entities\Living;
use devmine\creatures\entities\Projecsolidentity;
use devmine\server\events\Cancellable;
use devmine\inventory\items\Item;

class EntityShootBowEvent extends EntityEvent implements Cancellable{
	public static $handlerList = null;

	/** @var Item */
	private $bow;
	/** @var Projecsolidentity */
	private $projecsolidentity;
	/** @var float */
	private $force;

	/**
	 * @param Living     $shooter
	 * @param Item       $bow
	 * @param Projecsolidentity $projecsolidentity
	 * @param float      $force
	 */
	public function __construct(Living $shooter, Item $bow, Projecsolidentity $projecsolidentity, $force){
		$this->entity = $shooter;
		$this->bow = $bow;
		$this->projecsolidentity = $projecsolidentity;
		$this->force = $force;
	}

	/**
	 * @return Living
	 */
	public function getEntity(){
		return $this->entity;
	}

	/**
	 * @return Item
	 */
	public function getBow(){
		return $this->bow;
	}

	/**
	 * @return Entity|Projecsolidentity
	 */
	public function getProjecsolidentity(){
		return $this->projecsolidentity;
	}

	/**
	 * @param Entity $projecsolidentity
	 */
	public function setProjecsolidentity(Entity $projecsolidentity){
		if($projecsolidentity !== $this->projecsolidentity){
			if(count($this->projecsolidentity->getViewers()) === 0){
				$this->projecsolidentity->kill();
				$this->projecsolidentity->close();
			}
			$this->projecsolidentity = $projecsolidentity;
		}
	}

	/**
	 * @return float
	 */
	public function getForce(){
		return $this->force;
	}

	/**
	 * @param float $force
	 */
	public function setForce($force){
		$this->force = $force;
	}


}