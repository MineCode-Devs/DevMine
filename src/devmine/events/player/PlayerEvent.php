<?php



/**
 * Player-only related events
 */
namespace devmine\events\player;

use devmine\events\Event;

abstract class PlayerEvent extends Event{
	/** @var \devmine\creatures\player */
	protected $player;

	public function getPlayer(){
		return $this->player;
	}
}