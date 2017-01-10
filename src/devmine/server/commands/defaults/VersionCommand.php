<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;
use devmine\server\network\protocol\Info;
use devmine\consumer\plugin\Plugin;
use devmine\utilities\main\TextFormat;

class VersionCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.version.description",
			"%DevMine.command.version.usage",
			["ver", "about"]
		);
		$this->setPermission("DevMine.command.version");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return \true;
		}

		if(\count($args) === 0){
			$sender->sendMessage(new TranslationContainer("DevMine.server.info.extended.title"));
			$sender->sendMessage(new TranslationContainer("DevMine.server.info.extended1", [
											$sender->getServer()->getName(), 
											$sender->getServer()->getFormattedVersion("-"),
											$sender->getServer()->getCodename()
			]));
			$sender->sendMessage(new TranslationContainer("DevMine.server.info.extended2", [
											phpversion()
			]));
			$sender->sendMessage(new TranslationContainer("DevMine.server.info.extended3", [
											$sender->getServer()->getApiVersion()
			
			]));
			$sender->sendMessage(new TranslationContainer("DevMine.server.info.extended4", [
											$sender->getServer()->getVersion()											 
			]));
			$sender->sendMessage(new TranslationContainer("DevMine.server.info.extended5", [
											Info::CURRENT_PROTOCOL
			]));
		}else{
			$pluginName = \implode(" ", $args);
			$exactPlugin = $sender->getServer()->getPluginManager()->getPlugin($pluginName);

			if($exactPlugin instanceof Plugin){
				$this->describeToSender($exactPlugin, $sender);

				return \true;
			}

			$found = \false;
			$pluginName = \strtolower($pluginName);
			foreach($sender->getServer()->getPluginManager()->getPlugins() as $plugin){
				if(\stripos($plugin->getName(), $pluginName) !== \false){
					$this->describeToSender($plugin, $sender);
					$found = \true;
				}
			}

			if(!$found){
				$sender->sendMessage(new TranslationContainer("DevMine.command.version.noSuchPlugin"));
			}
		}

		return \true;
	}

	private function describeToSender(Plugin $plugin, CommandSender $sender){
		$desc = $plugin->getDescription();
		$sender->sendMessage(TextFormat::DARK_GREEN . $desc->getName() . TextFormat::WHITE . " version " . TextFormat::DARK_GREEN . $desc->getVersion());

		if($desc->getDescription() != \null){
			$sender->sendMessage($desc->getDescription());
		}

		if($desc->getWebsite() != \null){
			$sender->sendMessage("Website: " . $desc->getWebsite());
		}

		if(\count($authors = $desc->getAuthors()) > 0){
			if(\count($authors) === 1){
				$sender->sendMessage("Author: " . \implode(", ", $authors));
			}else{
				$sender->sendMessage("Authors: " . \implode(", ", $authors));
			}
		}
	}
}