<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;
use devmine\Player;
use devmine\utilities\main\TextFormat;

class OpCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.op.description",
			"%commands.op.usage"
		);
		$this->setPermission("devmine.command.op.give");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 0){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		$name = array_shift($args);

		$player = $sender->getServer()->getOfflinePlayer($name);
		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.op.success", [$player->getName()]));
		if($player instanceof Player){
			$player->sendMessage(TextFormat::GRAY . "You are now op!");
		}
		$player->setOp(true);
		return true;
	}
}