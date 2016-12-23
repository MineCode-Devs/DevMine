<?php


 
namespace synapse\event\player;

use synapse\event\Event;

abstract class PlayerEvent extends Event{
	/** @var \synapse\Player */
	protected $player;

	public function getPlayer(){
		return $this->player;
	}
}