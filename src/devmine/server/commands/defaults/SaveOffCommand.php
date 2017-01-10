<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;


class SaveOffCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.saveoff.description",
			"%commands.save-off.usage"
		);
		$this->setPermission("DevMine.command.save.disable");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		$sender->getServer()->setAutoSave(false);

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.save.disabled"));

		return true;
	}
}