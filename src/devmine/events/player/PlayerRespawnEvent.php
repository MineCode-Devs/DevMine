<?php



namespace devmine\events\player;

use devmine\worlds\Position;
use devmine\creatures\player;

/**
 * Called when a player is respawned (or first time spawned)
 */
class PlayerRespawnEvent extends PlayerEvent{
	public static $handlerList = null;

	/** @var Position */
	protected $position;

	/**
	 * @param Player   $player
	 * @param Position $position
	 */
	public function __construct(Player $player, Position $position){
		$this->player = $player;
		$this->position = $position;
	}

	/**
	 * @return Position
	 */
	public function getRespawnPosition(){
		return $this->position;
	}

	/**
	 * @param Position $position
	 */
	public function setRespawnPosition(Position $position){
		$this->position = $position;
	}
}