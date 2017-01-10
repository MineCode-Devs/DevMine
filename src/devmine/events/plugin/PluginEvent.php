<?php



/**
 * Events related Plugin enable / disable events
 */
namespace devmine\events\plugin;

use devmine\events\Event;
use devmine\consumer\plugin\Plugin;


abstract class PluginEvent extends Event{

	/** @var Plugin */
	private $plugin;

	public function __construct(Plugin $plugin){
		$this->plugin = $plugin;
	}

	/**
	 * @return Plugin
	 */
	public function getPlugin(){
		return $this->plugin;
	}
}
