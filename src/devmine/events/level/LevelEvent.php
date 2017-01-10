<?php



/**
 * Level related events
 */
namespace devmine\events\level;

use devmine\events\Event;
use devmine\worlds\Level;

abstract class LevelEvent extends Event{
	/** @var \devmine\worlds\Level */
	private $level;

	/**
	 * @param Level $level
	 */
	public function __construct(Level $level){
		$this->level = $level;
	}

	/**
	 * @return \devmine\worlds\Level
	 */
	public function getLevel(){
		return $this->level;
	}
}