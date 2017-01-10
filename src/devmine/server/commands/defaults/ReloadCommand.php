<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;
use devmine\utilities\main\TextFormat;

class ReloadCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.reload.description",
			"%DevMine.command.reload.usage"
		);
		$this->setPermission("DevMine.command.reload");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		Command::broadcastCommandMessage($sender, new TranslationContainer(TextFormat::YELLOW . "%DevMine.command.reload.reloading"));

		$sender->getServer()->reload();
		Command::broadcastCommandMessage($sender, new TranslationContainer(TextFormat::YELLOW . "%DevMine.command.reload.reloaded"));

		return true;
	}
}
