<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;
use devmine\creatures\player;
use devmine\utilities\main\TextFormat;

class MeCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.me.description",
			"%commands.me.usage"
		);
		$this->setPermission("DevMine.command.me");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 0){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		$sender->getServer()->broadcastMessage(new TranslationContainer("chat.type.emote", [$sender instanceof Player ? $sender->getDisplayName() : $sender->getName(), TextFormat::WHITE . implode(" ", $args)]));

		return true;
	}
}