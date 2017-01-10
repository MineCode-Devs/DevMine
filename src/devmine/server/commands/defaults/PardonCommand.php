<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;


class PardonCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.unban.player.description",
			"%commands.unban.usage"
		);
		$this->setPermission("DevMine.command.unban.player");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) !== 1){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		$sender->getServer()->getNameBans()->remove($args[0]);

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.unban.success", [$args[0]]));

		return true;
	}
}
