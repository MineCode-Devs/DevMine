<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\server\commands\ConsoleCommandSender;
use devmine\server\events\TranslationContainer;
use devmine\Player;
use devmine\utilities\main\TextFormat;

class XpCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.xp.description",
			"%commands.xp.usage"
		);
		$this->setPermission("devmine.command.xp");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) != 2){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
			return false;
		}else{
			$player = $sender->getServer()->getPlayer($args[1]);
			if($player instanceof Player){
				$name = $player->getName();
				if(strcasecmp(substr($args[0], -1), "L") == 0){			//Set Experience Level(with "L" after args[0])
					$level = rtrim($args[0], "Ll");
					if(is_numeric($level)){
						$player->addExpLevel($level);
						$sender->sendMessage("Successfully added $level Level of experience to $name");
					}
				}elseif(is_numeric($args[0])){											//Set Experience
					$player->addExperience($args[0]);
					$sender->sendMessage("Successfully added $args[0] of experience to $name");
				}else{
					$sender->sendMessage("Argument error");
					return false;
				}
			}else{
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.player.notFound"));
				return false;
			}
		}
		return false;
	}
}
