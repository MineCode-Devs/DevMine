<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;


class PardonIpCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.unban.ip.description",
			"%commands.unbanip.usage"
		);
		$this->setPermission("DevMine.command.unban.ip");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) !== 1){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if(preg_match("/^([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])\\.([01]?\\d\\d?|2[0-4]\\d|25[0-5])$/", $args[0])){
			$sender->getServer()->getIPBans()->remove($args[0]);
			Command::broadcastCommandMessage($sender, new TranslationContainer("commands.unbanip.success", [$args[0]]));
			$sender->getServer()->getNetwork()->unblockAddress($args[0]);
		}else{
			$sender->sendMessage(new TranslationContainer("commands.unbanip.invalid"));
		}

		return true;
	}
}
