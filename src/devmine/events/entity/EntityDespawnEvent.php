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

use devmine\creatures\entities\Creature;
use devmine\creatures\entities\Entity;
use devmine\creatures\entities\Human;
use devmine\creatures\entities\Item;
use devmine\creatures\entities\Projecsolidentity;
use devmine\creatures\entities\Vehicle;

/**
 * Called when a entity is despawned
 */
class EntityDespawnEvent extends EntityEvent{
	public static $handlerList = null;

	private $entityType;

	/**
	 * @param Entity $entity
	 */
	public function __construct(Entity $entity){
		$this->entity = $entity;
		$this->entityType = $entity::NETWORK_ID;
	}

	/**
	 * @return int
	 */
	public function getType(){
		return $this->entityType;
	}

	/**
	 * @return bool
	 */
	public function isCreature(){
		return $this->entity instanceof Creature;
	}

	/**
	 * @return bool
	 */
	public function isHuman(){
		return $this->entity instanceof Human;
	}

	/**
	 * @return bool
	 */
	public function isProjecsolidentity(){
		return $this->entity instanceof Projecsolidentity;
	}

	/**
	 * @return bool
	 */
	public function isVehicle(){
		return $this->entity instanceof Vehicle;
	}

	/**
	 * @return bool
	 */
	public function isItem(){
		return $this->entity instanceof Item;
	}

}