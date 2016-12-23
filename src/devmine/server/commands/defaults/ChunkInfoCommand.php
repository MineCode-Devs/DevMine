<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;
use devmine\levels\format\mcregion\McRegion;
use devmine\levels\Level;
use devmine\levels\Position;
use devmine\Player;
use devmine\utilities\main\TextFormat;

class ChunkInfoCommand extends VanillaCommand{
	public function __construct($name){
		parent::__construct(
			$name,
			"Gets the information of a chunk or regenerate a chunk",
			"/chunkinfo (x) (y) (z) (levelName) (regenerate)"
		);
		$this->setPermission("devmine.command.chunkinfo");
	}

	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(!$sender instanceof Player and count($args) < 4){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if($sender instanceof Player and count($args) < 4){
			$pos = $sender->getPosition();
		}else{
			$level = $sender->getServer()->getLevelByName($args[3]);
			if(!$level instanceof Level){
				$sender->sendMessage(TextFormat::RED . "Invalid level name");

				return false;
			}
			$pos = new Position((int) $args[0], (int) $args[1], (int) $args[2], $level);
		}

		if(!isset($args[4]) or $args[0] != "regenerate"){
			$chunk = $pos->getLevel()->getChunk($pos->x >> 4, $pos->z >> 4);
			McRegion::getRegionIndex($chunk->getX(), $chunk->getZ(), $x, $z);

			$sender->sendMessage("Region X: $x Region Z: $z");
		}elseif($args[4] == "regenerate"){
			foreach($sender->getServer()->getOnlinePlayers() as $p){
				if($p->getLevel() == $pos->getLevel()){
					$p->kick(TextFormat::AQUA . "A chunk of this chunk is regenerating, please re-login.", false);
				}
			}
			$pos->getLevel()->regenerateChunk($pos->x >> 4, $pos->z >> 4);
		}

		return true;
	}
}