<?php



/**
 * Events related Plugin enable / disable events
 */
namespace devmine\server\events\plugin;

use devmine\server\events\Event;
use devmine\pluginfeatures\Plugin;


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
