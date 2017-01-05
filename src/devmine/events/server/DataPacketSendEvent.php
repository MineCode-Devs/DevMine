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

namespace devmine\server\events\server;

use devmine\server\events;
use devmine\server\events\Cancellable;
use devmine\server\network\protocol\DataPacket;
use devmine\Player;

class DataPacketSendEvent extends ServerEvent implements Cancellable{
	public static $handlerList = null;

	private $packet;
	private $player;

	public function __construct(Player $player, DataPacket $packet){
		$this->packet = $packet;
		$this->player = $player;
	}

	public function getPacket(){
		return $this->packet;
	}

	public function getPlayer(){
		return $this->player;
	}
}