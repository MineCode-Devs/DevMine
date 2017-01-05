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