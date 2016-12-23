<?php



namespace devmine\server\events\player;

use devmine\server\events\Cancellable;
use devmine\Player;

/**
 * Called when a player is sent to some messages by using sendMessage, sendPopup and sendTip
 */
class PlayerTextPreSendEvent extends PlayerEvent implements Cancellable{
	const MESSAGE = 0;
	const POPUP = 1;
	const TIP = 2;
	const TRANSLATED_MESSAGE = 3;

	public static $handlerList = null;

	protected $message;
	protected $type = self::MESSAGE;

	public function __construct(Player $player, $message, $type = self::MESSAGE){
		$this->player = $player;
		$this->message = $message;
		$this->type = $type;
	}

	public function getMessage(){
		return $this->message;
	}

	public function setMessage($message){
		$this->message = $message;
	}

	public function getType(){
		return $this->type;
	}

}