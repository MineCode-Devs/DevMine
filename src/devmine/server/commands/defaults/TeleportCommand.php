<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;
use devmine\server\calculations\Vector3;
use devmine\Player;
use devmine\utilities\main\TextFormat;

class TeleportCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.tp.description",
			"%commands.tp.usage"
		);
		$this->setPermission("devmine.command.teleport");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 1 or count($args) > 6){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return true;
		}

		$target = null;
		$origin = $sender;

		if(count($args) === 1 or count($args) === 3){
			if($sender instanceof Player){
				$target = $sender;
			}else{
				$sender->sendMessage(TextFormat::RED . "Please provide a player!");

				return true;
			}
			if(count($args) === 1){
				$target = $sender->getServer()->getPlayer($args[0]);
				if($target === null){
					$sender->sendMessage(TextFormat::RED . "Can't find player " . $args[0]);

					return true;
				}
			}
		}else{
			$target = $sender->getServer()->getPlayer($args[0]);
			if($target === null){
				$sender->sendMessage(TextFormat::RED . "Can't find player " . $args[0]);

				return true;
			}
			if(count($args) === 2){
				$origin = $target;
				$target = $sender->getServer()->getPlayer($args[1]);
				if($target === null){
					$sender->sendMessage(TextFormat::RED . "Can't find player " . $args[1]);

					return true;
				}
			}
		}

		if(count($args) < 3){
			$origin->teleport($target);
			Command::broadcastCommandMessage($sender, new TranslationContainer("commands.tp.success", [$origin->getName(), $target->getName()]));

			return true;
		}elseif($target->getLevel() !== null){
			if(count($args) === 4 or count($args) === 6){
				$pos = 1;
			}else{
				$pos = 0;
			}

			$x = $this->getRelativeDouble($target->x, $sender, $args[$pos++]);
			$y = $this->getRelativeDouble($target->y, $sender, $args[$pos++], 0, 128);
			$z = $this->getRelativeDouble($target->z, $sender, $args[$pos++]);
			$yaw = $target->getYaw();
			$pitch = $target->getPitch();

			if(count($args) === 6 or (count($args) === 5 and $pos === 3)){
				$yaw = $args[$pos++];
				$pitch = $args[$pos++];
			}

			$target->teleport(new Vector3($x, $y, $z), $yaw, $pitch);
			Command::broadcastCommandMessage($sender, new TranslationContainer("commands.tp.success.coordinates", [$target->getName(), round($x, 2), round($y, 2), round($z, 2)]));

			return true;
		}

		$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

		return true;
	}
}
