<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;


class StopCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.stop.description",
			"%commands.stop.usage"
		);
		$this->setPermission("devmine.command.stop");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		$msg = "";
		if(isset($args[0])){
			$msg = $args[0];
		}

		$restart = false;
		if(isset($args[1])){
			if($args[0] == 'force'){
				$restart = true;
			}else{
				$restart = false;
			}
		}

		Command::broadcastCommandMessage($sender, new TranslationContainer("commands.stop.start"));

		$sender->getServer()->shutdown($restart, $msg);

		return true;
	}
}