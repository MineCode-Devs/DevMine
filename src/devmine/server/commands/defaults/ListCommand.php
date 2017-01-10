<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\events\TranslationContainer;
use devmine\creatures\player;


class ListCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.list.description",
			"%command.players.usage"
		);
		$this->setPermission("DevMine.command.list");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		$online = "";
		$onlineCount = 0;

		foreach($sender->getServer()->getOnlinePlayers() as $player){
			if($player->isOnline() and (!($sender instanceof Player) or $sender->canSee($player))){
				$online .= $player->getDisplayName() . ", ";
				++$onlineCount;
			}
		}

		$sender->sendMessage(new TranslationContainer("commands.players.list", [$onlineCount, $sender->getServer()->getMaxPlayers()]));
		$sender->sendMessage(substr($online, 0, -2));

		return true;
	}
}