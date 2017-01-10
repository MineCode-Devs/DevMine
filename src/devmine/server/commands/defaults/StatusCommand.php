<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Mostly by PocketMine team, modified by DevMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\creatures\player;
use devmine\utilities\main\TextFormat;
use devmine\utilities\main\Utils;

class StatusCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.status.description",
			"%DevMine.command.status.usage"
		);
		$this->setPermission("DevMine.command.status");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		$mUsage = Utils::getMemoryUsage(true);
		$rUsage = Utils::getRealMemoryUsage();

		$server = $sender->getServer();
		$onlineCount = 0;
		foreach($sender->getServer()->getOnlinePlayers() as $player){
			if($player->isOnline() and (!($sender instanceof Player) or $sender->canSee($player))){
				++$onlineCount;
			}
		}
		$sender->sendMessage(TextFormat::GREEN . "---- " . TextFormat::WHITE . "%DevMine.command.status.title" . TextFormat::GREEN . " ----");
		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.player" . TextFormat::GREEN ." ". $onlineCount . "/" . $sender->getServer()->getMaxPlayers());

		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.uptime " . TextFormat::RED . $sender->getServer()->getUptime());

		$tpsColor = TextFormat::GREEN;
		if($server->getTicksPerSecondAverage() < 10){
			$tpsColor = TextFormat::GOLD;
		}elseif($server->getTicksPerSecondAverage() < 1){
			$tpsColor = TextFormat::RED;
		}

		$tpsColour = TextFormat::GREEN;
		if($server->getTicksPerSecond() < 10){
			$tpsColour = TextFormat::GOLD;
		}elseif($server->getTicksPerSecond() < 1){
			$tpsColour = TextFormat::RED;
		}

		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.AverageTPS " . $tpsColor . $server->getTicksPerSecondAverage() . " (" . $server->getTickUsageAverage() . "%)");
		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.CurrentTPS " . $tpsColour . $server->getTicksPerSecond() . " (" . $server->getTickUsage() . "%)");

		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.Networkupload " . TextFormat::RED . \round($server->getNetwork()->getUpload() / 1024, 2) . " kB/s");
		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.Networkdownload " . TextFormat::RED . \round($server->getNetwork()->getDownload() / 1024, 2) . " kB/s");

		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.Threadcount " . TextFormat::RED . Utils::getThreadCount());

		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.Mainmemory " . TextFormat::RED . number_format(round(($mUsage[0] / 1024) / 1024, 2)) . " MB.");
		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.Totalmemory " . TextFormat::RED . number_format(round(($mUsage[1] / 1024) / 1024, 2)) . " MB.");
		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.Totalvirtualmemory " . TextFormat::RED . number_format(round(($mUsage[2] / 1024) / 1024, 2)) . " MB.");
		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.Heapmemory " . TextFormat::RED . number_format(round(($rUsage[0] / 1024) / 1024, 2)) . " MB.");
		$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.Maxmemorysystem " . TextFormat::RED . number_format(round(($mUsage[2] / 1024) / 1024, 2)) . " MB.");

		if($server->getProperty("memory.global-limit") > 0){
			$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.Maxmemorymanager " . TextFormat::RED . number_format(round($server->getProperty("memory.global-limit"), 2)) . " MB.");
		}
		foreach($server->getLevels() as $level){
			$sender->sendMessage(TextFormat::GOLD . "%DevMine.command.status.World \"" . $level->getFolderName() . "\"" . ($level->getFolderName() !== $level->getName() ? " (" . $level->getName() . ")" : "") . ": " .
				TextFormat::RED . number_format(count($level->getChunks())) . TextFormat::GREEN . " %DevMine.command.status.chunks " .
				TextFormat::RED . number_format(count($level->getEntities())) . TextFormat::GREEN . " %DevMine.command.status.entities " .
				TextFormat::RED . number_format(count($level->getTiles())) . TextFormat::GREEN . " %DevMine.command.status.tiles " .
				"%DevMine.command.status.Time " . (($level->getTickRate() > 1 or $level->getTickRateTime() > 40) ? TextFormat::RED : TextFormat::YELLOW) . round($level->getTickRateTime(), 2) . "%DevMine.command.status.ms" . ($level->getTickRate() > 1 ? " (tick rate " . $level->getTickRate() . ")" : "")
			);
		}

		return true;
	}
}
