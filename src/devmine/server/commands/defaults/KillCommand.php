<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\events\entity\EntityDamageEvent;
use devmine\events\TranslationContainer;
use devmine\creatures\player;
use devmine\utilities\main\TextFormat;

class KillCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.kill.description",
			"%DevMine.command.kill.usage",
			["suicide"]
		);
		$this->setPermission("DevMine.command.kill.self;DevMine.command.kill.other");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) >= 2){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if(count($args) === 1){
			if(!$sender->hasPermission("DevMine.command.kill.other")){
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}

			$player = $sender->getServer()->getPlayer($args[0]);

			if($player instanceof Player){
				$sender->getServer()->getPluginManager()->callEvent($ev = new EntityDamageEvent($player, EntityDamageEvent::CAUSE_SUICIDE, 1000));

				if($ev->isCancelled()){
					return true;
				}

				$player->setLastDamageCause($ev);
				$player->setHealth(0);

				Command::broadcastCommandMessage($sender, new TranslationContainer("commands.kill.successful", [$player->getName()]));
			}else{
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.player.notFound"));
			}

			return true;
		}

		if($sender instanceof Player){
			if(!$sender->hasPermission("DevMine.command.kill.self")){
				$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.permission"));

				return true;
			}

			$sender->getServer()->getPluginManager()->callEvent($ev = new EntityDamageEvent($sender, EntityDamageEvent::CAUSE_SUICIDE, 1000));

			if($ev->isCancelled()){
				return true;
			}

			$sender->setLastDamageCause($ev);
			$sender->setHealth(0);
			$sender->sendMessage(new TranslationContainer("commands.kill.successful", [$sender->getName()]));
		}else{
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		return true;
	}
}