<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;
use devmine\Server;


class DefaultGamemodeCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.defaultgamemode.description",
			"%commands.defaultgamemode.usage"
		);
		$this->setPermission("devmine.command.defaultgamemode");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 0){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		$gameMode = Server::getGamemodeFromString($args[0]);

		if($gameMode !== -1){
			$sender->getServer()->setConfigInt("gamemode", $gameMode);
			$sender->sendMessage(new TranslationContainer("commands.defaultgamemode.success", [Server::getGamemodeString($gameMode)]));
		}else{
			$sender->sendMessage("You entered an unknown gamemode");
		}

		return true;
	}
}
