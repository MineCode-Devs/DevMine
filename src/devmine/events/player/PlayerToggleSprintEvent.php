<?php



namespace devmine\events\player;

use devmine\events\Cancellable;
use devmine\creatures\player;

class PlayerToggleSprintEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	/** @var bool */
	protected $isSprinting;

	public function __construct(Player $player, $isSprinting){
		$this->player = $player;
		$this->isSprinting = (bool) $isSprinting;
	}

	public function isSprinting(){
		return $this->isSprinting;
	}

}