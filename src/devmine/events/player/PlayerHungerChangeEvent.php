<?php
namespace devmine\server\events\player;

use devmine\server\events\Cancellable;
use devmine\Player;

class PlayerHungerChangeEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;
	
	public $data;

	public function __construct(Player $player, $data){
		$this->data = $data;
		$this->player = $player;
	}
	
	public function getData(){
		return $this->data;
	}
	
	public function setData($data){
		$this->data = $data;
	}

}
