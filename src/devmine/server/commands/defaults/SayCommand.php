<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\server\commands\ConsoleCommandSender;
use devmine\events\TranslationContainer;
use devmine\creatures\player;
use devmine\utilities\main\TextFormat;

class SayCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.say.description",
			"%commands.say.usage",
			["broadcast", "announce"]
		);
		$this->setPermission("DevMine.command.say");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 0){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		$sender->getServer()->broadcastMessage(new TranslationContainer(TextFormat::LIGHT_PURPLE . "%chat.type.announcement", [$sender instanceof Player ? $sender->getDisplayName() : ($sender instanceof ConsoleCommandSender ? "Server" : $sender->getName()), TextFormat::LIGHT_PURPLE . implode(" ", $args)]));
		return true;
	}
}
