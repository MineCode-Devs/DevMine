<?php


 
namespace synapse\event\player;

use synapse\Player;

class PlayerConnectEvent extends PlayerEvent{
	public static $handlerList = null;

	/** @var bool */
	private $firstTime;

	public function __construct(Player $player, bool $firstTime = true){
		$this->player = $player;
		$this->firstTime = $firstTime;
	}

	/**
	 * Gets if the player is first time login
	 *
	 * @return bool
	 */
	public function isFirstTime() : bool{
		return $this->firstTime;
	}
}