<?php



namespace devmine\server\commands;

interface PluginIdentifiableCommand{

	/**
	 * @return \devmine\pluginfeatures\Plugin
	 */
	public function getPlugin();
}
