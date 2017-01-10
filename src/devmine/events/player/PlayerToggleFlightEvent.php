<?php



namespace devmine\events\player;

use devmine\events\Cancellable;
use devmine\creatures\player;

class PlayerToggleFlightEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	/** @var bool */
	protected $isFlying;

	public function __construct(Player $player, $isFlying){
		$this->player = $player;
		$this->isFlying = (bool) $isFlying;
	}

	public function isFlying(){
		return $this->isFlying;
	}

}