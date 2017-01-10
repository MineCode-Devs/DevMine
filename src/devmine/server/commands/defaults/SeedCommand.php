<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;
use devmine\creatures\player;


class SeedCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.seed.description",
			"%commands.seed.usage"
		);
		$this->setPermission("DevMine.command.seed");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if($sender instanceof Player){
			$seed = $sender->getLevel()->getSeed();
		}else{
			$seed = $sender->getServer()->getDefaultLevel()->getSeed();
		}
		$sender->sendMessage(new TranslationContainer("commands.seed.success", [$seed]));

		return true;
	}
}