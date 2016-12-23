<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;
use devmine\levels\Level;
use devmine\Player;
use devmine\utilities\main\TextFormat;

class TimeCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.time.description",
			"%devmine.command.time.usage"
		);
		$this->setPermission("devmine.command.time.add;devmine.command.time.set;devmine.command.time.start;devmine.command.time.stop");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(count($args) < 1){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if($args[0] === "start"){
			if(!$sender->hasPermission("devmine.command.time.start")){
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}
			foreach($sender->getServer()->getLevels() as $level){
				$level->checkTime();
				$level->startTime();
				$level->checkTime();
			}
			Command::broadcastCommandMessage($sender, "Restarted the time");
			return true;
		}elseif($args[0] === "stop"){
			if(!$sender->hasPermission("devmine.command.time.stop")){
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}
			foreach($sender->getServer()->getLevels() as $level){
				$level->checkTime();
				$level->stopTime();
				$level->checkTime();
			}
			Command::broadcastCommandMessage($sender, "Stopped the time");
			return true;
		}elseif($args[0] === "query"){
			if(!$sender->hasPermission("devmine.command.time.query")){
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}
			if($sender instanceof Player){
				$level = $sender->getLevel();
			}else{
				$level = $sender->getServer()->getDefaultLevel();
			}
			$sender->sendMessage(new TranslationContainer("commands.time.query", [$level->getTime()]));
			return true;
		}


		if(count($args) < 2){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if($args[0] === "set"){
			if(!$sender->hasPermission("devmine.command.time.set")){
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}

			if($args[1] === "day"){
				$value = 0;
			}elseif($args[1] === "night"){
				$value = Level::TIME_NIGHT;
			}else{
				$value = $this->getInteger($sender, $args[1], 0);
			}

			foreach($sender->getServer()->getLevels() as $level){
				$level->checkTime();
				$level->setTime($value);
				$level->checkTime();
			}
			Command::broadcastCommandMessage($sender, new TranslationContainer("commands.time.set", [$value]));
		}elseif($args[0] === "add"){
			if(!$sender->hasPermission("devmine.command.time.add")){
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}

			$value = $this->getInteger($sender, $args[1], 0);
			foreach($sender->getServer()->getLevels() as $level){
				$level->checkTime();
				$level->setTime($level->getTime() + $value);
				$level->checkTime();
			}
			Command::broadcastCommandMessage($sender, new TranslationContainer("commands.time.added", [$value]));
		}else{
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
		}

		return true;
	}
}