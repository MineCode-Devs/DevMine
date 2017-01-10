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

namespace devmine\server\commands;

use devmine\server\commands\defaults\BanCidByNameCommand;
use devmine\server\commands\defaults\BanCidCommand;
use devmine\server\commands\defaults\BanCommand;
use devmine\server\commands\defaults\BanIpByNameCommand;
use devmine\server\commands\defaults\BanIpCommand;
use devmine\server\commands\defaults\BanListCommand;
use devmine\server\commands\defaults\DefaultGamemodeCommand;
use devmine\server\commands\defaults\DeopCommand;
use devmine\server\commands\defaults\DifficultyCommand;
use devmine\server\commands\defaults\DumpMemoryCommand;
use devmine\server\commands\defaults\EffectCommand;
use devmine\server\commands\defaults\EnchantCommand;
use devmine\server\commands\defaults\GamemodeCommand;
use devmine\server\commands\defaults\GarbageCollectorCommand;
use devmine\server\commands\defaults\GiveCommand;
use devmine\server\commands\defaults\HelpCommand;
use devmine\server\commands\defaults\KickCommand;
use devmine\server\commands\defaults\KillCommand;
use devmine\server\commands\defaults\ListCommand;
use devmine\server\commands\defaults\MeCommand;
use devmine\server\commands\defaults\OpCommand;
use devmine\server\commands\defaults\PardonCommand;
use devmine\server\commands\defaults\PardonCidCommand;
use devmine\server\commands\defaults\PardonIpCommand;
use devmine\server\commands\defaults\ParticleCommand;
use devmine\server\commands\defaults\PluginsCommand;
use devmine\server\commands\defaults\ReloadCommand;
use devmine\server\commands\defaults\SaveCommand;
use devmine\server\commands\defaults\SaveOffCommand;
use devmine\server\commands\defaults\SaveOnCommand;
use devmine\server\commands\defaults\SayCommand;
use devmine\server\commands\defaults\SeedCommand;
use devmine\server\commands\defaults\SetBlockCommand;
use devmine\server\commands\defaults\SetWorldSpawnCommand;
use devmine\server\commands\defaults\SpawnpointCommand;
use devmine\server\commands\defaults\StatusCommand;
use devmine\server\commands\defaults\StopCommand;
use devmine\server\commands\defaults\SummonCommand;
use devmine\server\commands\defaults\TeleportCommand;
use devmine\server\commands\defaults\TellCommand;
use devmine\server\commands\defaults\TimeCommand;
use devmine\server\commands\defaults\TimingsCommand;
use devmine\server\commands\defaults\VanillaCommand;
use devmine\server\commands\defaults\VersionCommand;
use devmine\server\commands\defaults\WeatherCommand;
use devmine\server\commands\defaults\WhitelistCommand;
use devmine\server\commands\defaults\XpCommand;
use devmine\server\commands\defaults\MakePharCommand;

use devmine\events\TranslationContainer;
use devmine\creatures\player;
use devmine\server\server;
use devmine\utilities\main\MainLogger;
use devmine\utilities\main\TextFormat;

class SimpleCommandMap implements CommandMap{

	/**
	 * @var Command[]
	 */
	protected $knownCommands = [];
	
	/**
	 * @var bool[]
	 */
	protected $commandConfig = [];

	/** @var Server */
	private $server;

	public function __construct(Server $server){
		$this->server = $server;
		/** @var bool[] */
		$this->commandConfig = $this->server->getProperty("commands");
		$this->setDefaultCommands();
	}

	private function setDefaultCommands(){
		$this->register("DevMine", new WeatherCommand("weather"));

		$this->register("DevMine", new BanCidCommand("bancid"));
		$this->register("DevMine", new PardonCidCommand("pardoncid"));
		$this->register("DevMine", new BanCidByNameCommand("bancidbyname"));
		$this->register("DevMine", new BanIpByNameCommand("banipbyname"));

		$this->register("DevMine", new VersionCommand("version"));
		$this->register("DevMine", new PluginsCommand("plugins"));
		$this->register("DevMine", new SeedCommand("seed"));
		$this->register("DevMine", new HelpCommand("help"), null, true);
		$this->register("DevMine", new StopCommand("stop"), null, true);
		$this->register("DevMine", new TellCommand("tell"));
		$this->register("DevMine", new DefaultGamemodeCommand("defaultgamemode"));
		$this->register("DevMine", new BanCommand("ban"));
		$this->register("DevMine", new BanIpCommand("ban-ip"));
		$this->register("DevMine", new BanListCommand("banlist"));
		$this->register("DevMine", new PardonCommand("pardon"));
		$this->register("DevMine", new PardonIpCommand("pardon-ip"));
		$this->register("DevMine", new SayCommand("say"));
		$this->register("DevMine", new MeCommand("me"));
		$this->register("DevMine", new ListCommand("list"));
		$this->register("DevMine", new DifficultyCommand("difficulty"));
		$this->register("DevMine", new KickCommand("kick"));
		$this->register("DevMine", new OpCommand("op"));
		$this->register("DevMine", new DeopCommand("deop"));
		$this->register("DevMine", new WhitelistCommand("whitelist"));
		$this->register("DevMine", new SaveOnCommand("save-on"));
		$this->register("DevMine", new SaveOffCommand("save-off"));
		$this->register("DevMine", new SaveCommand("save-all"), null, true);
		$this->register("DevMine", new GiveCommand("give"));
		$this->register("DevMine", new EffectCommand("effect"));
		$this->register("DevMine", new EnchantCommand("enchant"));
		$this->register("DevMine", new ParticleCommand("particle"));
		$this->register("DevMine", new GamemodeCommand("gamemode"));
		$this->register("DevMine", new KillCommand("kill"));
		$this->register("DevMine", new SpawnpointCommand("spawnpoint"));
		$this->register("DevMine", new SetWorldSpawnCommand("setworldspawn"));
		$this->register("DevMine", new SummonCommand("summon"));
		$this->register("DevMine", new TeleportCommand("tp"));
		$this->register("DevMine", new TimeCommand("time"));
		$this->register("DevMine", new TimingsCommand("timings"));
		$this->register("DevMine", new ReloadCommand("reload"), null, true);
		$this->register("DevMine", new XpCommand("xp"));
		$this->register("DevMine", new SetBlockCommand("setblock"));

		if($this->server->getProperty("debug.commands", false)){
			$this->register("DevMine", new StatusCommand("status"), null, true);
			$this->register("DevMine", new GarbageCollectorCommand("gc"), null, true);
			$this->register("DevMine", new DumpMemoryCommand("dumpmemory"), null, true);
		}
	}


	public function registerAll($fallbackPrefix, array $commands){
		foreach($commands as $command){
			$this->register($fallbackPrefix, $command);
		}
	}

	public function register($fallbackPrefix, Command $command, $label = null, $overrideConfig = false){
		if($label === null){
			$label = $command->getName();
		}
		$label = strtolower(trim($label));
		
		//Check if command was disabled in config and for override
		if(!(($this->commandConfig[$label] ?? $this->commandConfig["default"] ?? true) or $overrideConfig)){
			return false;
		}
		$fallbackPrefix = strtolower(trim($fallbackPrefix));

		$registered = $this->registerAlias($command, false, $fallbackPrefix, $label);

		$aliases = $command->getAliases();
		foreach($aliases as $index => $alias){
			if(!$this->registerAlias($command, true, $fallbackPrefix, $alias)){
				unset($aliases[$index]);
			}
		}
		$command->setAliases($aliases);

		if(!$registered){
			$command->setLabel($fallbackPrefix . ":" . $label);
		}

		$command->register($this);

		return $registered;
	}

	private function registerAlias(Command $command, $isAlias, $fallbackPrefix, $label){
		$this->knownCommands[$fallbackPrefix . ":" . $label] = $command;
		if(($command instanceof VanillaCommand or $isAlias) and isset($this->knownCommands[$label])){
			return false;
		}

		if(isset($this->knownCommands[$label]) and $this->knownCommands[$label]->getLabel() !== null and $this->knownCommands[$label]->getLabel() === $label){
			return false;
		}

		if(!$isAlias){
			$command->setLabel($label);
		}

		$this->knownCommands[$label] = $command;

		return true;
	}

	private function dispatchAdvanced(CommandSender $sender, Command $command, $label, array $args, $offset = 0){
		if(isset($args[$offset])){
			$argsTemp = $args;
			switch($args[$offset]){
				case "@a":
					$p = $this->server->getOnlinePlayers();
					if(count($p) <= 0){
						$sender->sendMessage(TextFormat::RED . "No players online"); //TODO: add language
					}else{
						foreach($p as $player){
							$argsTemp[$offset] = $player->getName();
							$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
						}
					}
					break;
				case "@r":
					$players = $this->server->getOnlinePlayers();
					if(count($players) > 0){
						$argsTemp[$offset] = $players[array_rand($players)]->getName();
						$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
					}
					break;
				case "@p":
					if($sender instanceof Player){
						$argsTemp[$offset] = $sender->getName();
						$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
					}else{
						$sender->sendMessage(TextFormat::RED . "You must be a player!"); //TODO: add language
					}
					break;
				default:
					$this->dispatchAdvanced($sender, $command, $label, $argsTemp, $offset + 1);
			}
		}else $command->execute($sender, $label, $args);
	}

	public function dispatch(CommandSender $sender, $commandLine){
		$args = explode(" ", $commandLine);

		if(count($args) === 0){
			return false;
		}

		$sentCommandLabel = strtolower(array_shift($args));
		$target = $this->getCommand($sentCommandLabel);

		if($target === null){
			return false;
		}

		$target->timings->startTiming();
		try{
			if($this->server->advancedCommandSelector){
				$this->dispatchAdvanced($sender, $target, $sentCommandLabel, $args);
			}else{
				$target->execute($sender, $sentCommandLabel, $args);
			}
		}catch(\Throwable $e){
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.exception"));
			$this->server->getLogger()->critical($this->server->getLanguage()->translateString("DevMine.command.exception", [$commandLine, (string) $target, $e->getMessage()]));
			$logger = $sender->getServer()->getLogger();
			if($logger instanceof MainLogger){
				$logger->logException($e);
			}
		}
		$target->timings->stopTiming();

		return true;
	}

	public function clearCommands(){
		foreach($this->knownCommands as $command){
			$command->unregister($this);
		}
		$this->knownCommands = [];
		$this->setDefaultCommands();
	}

	public function getCommand($name){
		if(isset($this->knownCommands[$name])){
			return $this->knownCommands[$name];
		}

		return null;
	}

	/**
	 * @return Command[]
	 */
	public function getCommands(){
		return $this->knownCommands;
	}


	/**
	 * @return void
	 */
	public function registerServerAliases(){
		$values = $this->server->getCommandAliases();

		foreach($values as $alias => $commandStrings){
			if(strpos($alias, ":") !== false or strpos($alias, " ") !== false){
				$this->server->getLogger()->warning($this->server->getLanguage()->translateString("DevMine.command.alias.illegal", [$alias]));
				continue;
			}

			$targets = [];

			$bad = "";
			foreach($commandStrings as $commandString){
				$args = explode(" ", $commandString);
				$command = $this->getCommand($args[0]);

				if($command === null){
					if(strlen($bad) > 0){
						$bad .= ", ";
					}
					$bad .= $commandString;
				}else{
					$targets[] = $commandString;
				}
			}

			if(strlen($bad) > 0){
				$this->server->getLogger()->warning($this->server->getLanguage()->translateString("DevMine.command.alias.notFound", [$alias, $bad]));
				continue;
			}

			//These registered commands have absolute priority
			if(count($targets) > 0){
				$this->knownCommands[strtolower($alias)] = new FormattedCommandAlias(strtolower($alias), $targets);
			}else{
				unset($this->knownCommands[strtolower($alias)]);
			}

		}
	}


}
