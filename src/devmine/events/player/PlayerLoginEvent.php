<?php



namespace devmine\events\player;

use devmine\events\Cancellable;
use devmine\creatures\player;

/**
 * Called when a player joins, after things have been correctly set up (you can change anything now)
 */
class PlayerLoginEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	/** @var string */
	protected $kickMessage;

	public function __construct(Player $player, $kickMessage){
		$this->player = $player;
		$this->kickMessage = $kickMessage;
	}

	public function setKickMessage($kickMessage){
		$this->kickMessage = $kickMessage;
	}

	public function getKickMessage(){
		return $this->kickMessage;
	}

}