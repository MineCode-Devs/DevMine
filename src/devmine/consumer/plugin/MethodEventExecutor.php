<?php

/*
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
 * @author Mostly by PocketMine team, modified by DevMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace devmine\consumer\plugin;

use devmine\events\Event;
use devmine\events\Listener;

class MethodEventExecutor implements EventExecutor{

	private $method;

	public function __construct($method){
		$this->method = $method;
	}

	public function execute(Listener $listener, Event $event){
		$listener->{$this->getMethod()}($event);
	}

	public function getMethod(){
		return $this->method;
	}
}