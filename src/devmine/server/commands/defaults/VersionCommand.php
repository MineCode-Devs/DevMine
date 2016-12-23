<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;
use devmine\server\network\protocol\Info;
use devmine\pluginfeatures\Plugin;
use devmine\utilities\main\TextFormat;

class VersionCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.version.description",
			"%devmine.command.version.usage",
			["ver", "about"]
		);
		$this->setPermission("devmine.command.version");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return \true;
		}

		if(\count($args) === 0){
			$sender->sendMessage(new TranslationContainer("devmine.server.info.extended", [
				$sender->getServer()->getName(),
				$sender->getServer()->getdevmineVersion(),
				$sender->getServer()->getCodename(),
				$sender->getServer()->getApiVersion(),
				$sender->getServer()->getVersion(),
				Info::CURRENT_PROTOCOL,
				$sender->getServer()->getiTXApiVersion()
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
				$sender->sendMessage(new TranslationContainer("devmine.command.version.noSuchPlugin"));
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