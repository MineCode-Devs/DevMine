<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;
use devmine\Server;


class BanListCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.banlist.description",
			"%commands.banlist.usage"
		);
		$this->setPermission("devmine.command.ban.list");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
		
		$args[0] = (isset($args[0]) ? strtolower($args[0]): "");
		$title = "";
		
		switch($args[0]){
			case "ips":
				$list = $sender->getServer()->getIPBans();	
				$title = "commands.banlist.ips";
				break;
			case "cids":
				$list = $list = $sender->getServer()->getCIDBans(); 
				$title = "commands.banlist.cids";
				break;
			case "players":
				$list = $sender->getServer()->getNameBans();
				$title = "commands.banlist.players";
				break;
			default:
				$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
				return false;			
		}
		
		$message = "";
		$list = $list->getEntries();
		foreach($list as $entry){
			$message .= $entry->getName() . ", ";
		}
		
		$sender->sendMessage(Server::getInstance()->getLanguage()->translateString($title, [count($list)]));
		$sender->sendMessage(\substr($message, 0, -2));

		return true;
	}
}