<?php



namespace devmine\consumer\plugin;

use devmine\events\plugin\PluginDisableEvent;
use devmine\events\plugin\PluginEnableEvent;
use devmine\server\server;
use devmine\utilities\main\PluginException;

/**
 * Handles different types of plugins
 */
class PharPluginLoader implements PluginLoader{

	/** @var Server */
	private $server;

	/**
	 * @param Server $server
	 */
	public function __construct(Server $server){
		$this->server = $server;
	}

	/**
	 * Loads the plugin contained in $file
	 *
	 * @param string $file
	 *
	 * @return Plugin
	 *
	 * @throws \Throwable
	 */
	public function loadPlugin($file){
		if(($description = $this->getPluginDescription($file)) instanceof PluginDescription){
			$this->server->getLogger()->info($this->server->getLanguage()->translateString("DevMine.plugin.load", [$description->getFullName()]));
			$dataFolder = dirname($file) . DIRECTORY_SEPARATOR . $description->getName();
			if(file_exists($dataFolder) and !is_dir($dataFolder)){
				throw new \InvalidStateException("Projected dataFolder '" . $dataFolder . "' for " . $description->getName() . " exists and is not a directory");
			}
			$file = "phar://$file";
			$className = $description->getMain();
			$this->server->getLoader()->addPath("$file/src");

			if(class_exists($className, true)){
				$plugin = new $className();
				$this->initPlugin($plugin, $description, $dataFolder, $file);

				return $plugin;
			}else{
				throw new PluginException("Couldn't load plugin " . $description->getName() . ": main class not found");
			}
		}

		return null;
	}

	/**
	 * Gets the PluginDescription from the file
	 *
	 * @param string $file
	 *
	 * @return PluginDescription
	 */
	public function getPluginDescription($file){
		$phar = new \Phar($file);
		if(isset($phar["plugin.yml"])){
			$pluginYml = $phar["plugin.yml"];
			if($pluginYml instanceof \PharFileInfo){
				return new PluginDescription($pluginYml->getContent());
			}
		}

		return null;
	}

	/**
	 * Returns the filename patterns that this loader accepts
	 *
	 * @return array
	 */
	public function getPluginFilters(){
		return "/\\.phar$/i";
	}

	/**
	 * @param PluginBase        $plugin
	 * @param PluginDescription $description
	 * @param string            $dataFolder
	 * @param string            $file
	 */
	private function initPlugin(PluginBase $plugin, PluginDescription $description, $dataFolder, $file){
		$plugin->init($this, $this->server, $description, $dataFolder, $file);
		$plugin->onLoad();
	}

	/**
	 * @param Plugin $plugin
	 */
	public function enablePlugin(Plugin $plugin){
		if($plugin instanceof PluginBase and !$plugin->isEnabled()){
			$this->server->getLogger()->info($this->server->getLanguage()->translateString("DevMine.plugin.enable", [$plugin->getDescription()->getFullName()]));

			$plugin->setEnabled(true);

			$this->server->getPluginManager()->callEvent(new PluginEnableEvent($plugin));
		}
	}

	/**
	 * @param Plugin $plugin
	 */
	public function disablePlugin(Plugin $plugin){
		if($plugin instanceof PluginBase and $plugin->isEnabled()){
			$this->server->getLogger()->info($this->server->getLanguage()->translateString("DevMine.plugin.disable", [$plugin->getDescription()->getFullName()]));

			$this->server->getPluginManager()->callEvent(new PluginDisableEvent($plugin));

			$plugin->setEnabled(false);
		}
	}
}