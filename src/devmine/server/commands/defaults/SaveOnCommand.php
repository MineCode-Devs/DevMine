<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;


class SaveOnCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.saveon.description",
			"%commands.save-on.usage"
		);
		$this->setPermission("devmine.command.save.enable");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		$sender->getServer()->setAutoSave(true);

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.save.enabled"));

		return true;
	}
}