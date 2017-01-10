<?php



namespace devmine\events\player;

use devmine\inventory\blocks\Block;
use devmine\events\Cancellable;
use devmine\creatures\player;

class PlayerBedEnterEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	private $bed;

	public function __construct(Player $player, Block $bed){
		$this->player = $player;
		$this->bed = $bed;
	}

	public function getBed(){
		return $this->bed;
	}

}