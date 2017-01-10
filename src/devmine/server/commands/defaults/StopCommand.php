<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;


class StopCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.stop.description",
			"%commands.stop.usage"
		);
		$this->setPermission("DevMine.command.stop");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
		$restart = false;
		if(isset($args[0])){
			if($args[0] == 'force'){
				$restart = true;
				array_shift($args);
			}else{
				$restart = false;
			}
		}
		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.stop.start"));
		$msg = implode(" ", $args);
		$sender->getServer()->shutdown($restart, $msg);

		return true;
	}
}