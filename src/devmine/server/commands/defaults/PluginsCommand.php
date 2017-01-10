<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;
use devmine\utilities\main\TextFormat;

class PluginsCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.plugins.description",
			"%DevMine.command.plugins.usage",
			["pl"]
		);
		$this->setPermission("DevMine.command.plugins");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
		$this->sendPluginList($sender);
		return true;
	}

	private function sendPluginList(CommandSender $sender){
		$list = "";
		foreach(($plugins = $sender->getServer()->getPluginManager()->getPlugins()) as $plugin){
			if(strlen($list) > 0){
				$list .= TextFormat::WHITE . ", ";
			}
			$list .= $plugin->isEnabled() ? TextFormat::GREEN : TextFormat::RED;
			$list .= $plugin->getDescription()->getFullName();
		}

		$sender->sendMessage(new TranslationContainer("DevMine.command.plugins.success", [count($plugins), $list]));
	}
}
