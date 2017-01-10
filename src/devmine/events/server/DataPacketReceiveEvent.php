<?php



namespace devmine\events\server;

use devmine\events;
use devmine\events\Cancellable;
use devmine\server\network\protocol\DataPacket;
use devmine\creatures\player;

class DataPacketReceiveEvent extends ServerEvent implements Cancellable{
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