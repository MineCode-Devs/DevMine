<?php



namespace devmine\events\level;

use devmine\worlds\Level;
use devmine\worlds\Position;

/**
 * An event that is called when a level spawn changes.
 * The previous spawn is included
 */
class SpawnChangeEvent extends LevelEvent{
	public static $handlerList = null;

	/** @var Position */
	private $previousSpawn;

	/**
	 * @param Level    $level
	 * @param Position $previousSpawn
	 */
	public function __construct(Level $level, Position $previousSpawn){
		parent::__construct($level);
		$this->previousSpawn = $previousSpawn;
	}

	/**
	 * @return Position
	 */
	public function getPreviousSpawn(){
		return $this->previousSpawn;
	}
}