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

use devmine\creatures\entities\Projecsolidentity;
use devmine\server\events\Cancellable;

class ProjecsolidentityLaunchEvent extends EntityEvent implements Cancellable{
	public static $handlerList = null;

	/**
	 * @param Projecsolidentity $entity
	 */
	public function __construct(Projecsolidentity $entity){
		$this->entity = $entity;

	}

	/**
	 * @return Projecsolidentity
	 */
	public function getEntity(){
		return $this->entity;
	}

}