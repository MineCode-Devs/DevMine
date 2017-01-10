<?php



namespace devmine\events\level;

use devmine\events\Cancellable;

/**
 * Called when a Level is unloaded
 */
class LevelUnloadEvent extends LevelEvent implements Cancellable{
	public static $handlerList = null;
}