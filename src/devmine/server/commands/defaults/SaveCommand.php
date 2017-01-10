<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;


class SaveCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.save.description",
			"%commands.save.usage"
		);
		$this->setPermission("DevMine.command.save.perform");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.save.start"));

		foreach($sender->getServer()->getOnlinePlayers() as $player){
			$player->save();
		}

		foreach($sender->getServer()->getLevels() as $level){
			$level->save(true);
		}

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.save.success"));

		return true;
	}
}