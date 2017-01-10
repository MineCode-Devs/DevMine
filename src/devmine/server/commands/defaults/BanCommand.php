<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;
use devmine\creatures\player;


class BanCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.ban.player.description",
			"%commands.ban.usage"
		);
		$this->setPermission("DevMine.command.ban.player");
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
		if(isset($args[0]) and isset($args[1])){
			$reason = $args[0];
			if($args[1] != null and is_numeric($args[1])){
				$until = new \DateTime('@' . ($args[1] * 86400 + time()));
			}else{
				$until = null;
			}

			$sender->getServer()->getNameBans()->addBan($name, $reason, $until, $sender->getName());
		}else{
			$sender->getServer()->getNameBans()->addBan($name, $reason = implode(" ", $args), null, $sender->getName());
		}


		if(($player = $sender->getServer()->getPlayerExact($name)) instanceof Player){
			$player->kick($reason !== "" ? "Banned by admin. Reason: " . $reason : "Banned by admin." . "Banned Until:" . date('r'), $until = "Forever");
		}

		Command::broadcastCommandMessage($sender, new TranslationContainer("%commands.ban.success", [$player !== null ? $player->getName() : $name]));

		return true;
	}
}
