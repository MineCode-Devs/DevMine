<?php



namespace devmine\server\commands;

interface PluginIdentifiableCommand{

	/**
	 * @return \devmine\consumer\plugin\Plugin
	 */
	public function getPlugin();
}
