<?php



namespace devmine\events\player;

use devmine\events\Cancellable;
use devmine\creatures\player;

class PlayerToggleSneakEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	/** @var bool */
	protected $isSneaking;

	public function __construct(Player $player, $isSneaking){
		$this->player = $player;
		$this->isSneaking = (bool) $isSneaking;
	}

	public function isSneaking(){
		return $this->isSneaking;
	}

}