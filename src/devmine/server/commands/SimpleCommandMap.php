<?php



namespace devmine\server\commands;

use devmine\server\commands\defaults\BanCommand;
use devmine\server\commands\defaults\BanIpCommand;
use devmine\server\commands\defaults\BanListCommand;
use devmine\server\commands\defaults\BiomeCommand;
use devmine\server\commands\defaults\CaveCommand;
use devmine\server\commands\defaults\ChunkInfoCommand;
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
use devmine\server\commands\defaults\LoadPluginCommand;
use devmine\server\commands\defaults\LvdatCommand;
use devmine\server\commands\defaults\MeCommand;
use devmine\server\commands\defaults\OpCommand;
use devmine\server\commands\defaults\PardonCommand;
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
use devmine\server\commands\defaults\WhitelistCommand;
use devmine\server\commands\defaults\XpCommand;
use devmine\server\commands\defaults\FillCommand;
use devmine\server\events\TranslationContainer;
use devmine\Player;
use devmine\Server;
use devmine\utilities\main\MainLogger;
use devmine\utilities\main\TextFormat;

use devmine\server\commands\defaults\MakeServerCommand;
use devmine\server\commands\defaults\ExtractPluginCommand;
use devmine\server\commands\defaults\ExtractPharCommand;
use devmine\server\commands\defaults\MakePluginCommand;
use devmine\server\commands\defaults\BancidbynameCommand;
use devmine\server\commands\defaults\BanipbynameCommand;
use devmine\server\commands\defaults\BanCidCommand;
use devmine\server\commands\defaults\PardonCidCommand;
use devmine\server\commands\defaults\WeatherCommand;

class SimpleCommandMap implements CommandMap{

	/**
	 * @var Command[]
	 */
	protected $knownCommands = [];

	/** @var Server */
	private $server;

	public function __construct(Server $server){
		$this->server = $server;
		$this->setDefaultCommands();
	}

	private function setDefaultCommands(){
		$this->register("devmine", new WeatherCommand("weather"));

		$this->register("devmine", new BanCidCommand("bancid"));
		$this->register("devmine", new PardonCidCommand("pardoncid"));
		$this->register("devmine", new BancidbynameCommand("bancidbyname"));
		$this->register("devmine", new BanipbynameCommand("banipbyname"));

		$this->register("devmine", new ExtractPharCommand("extractphar"));
		$this->register("devmine", new ExtractPluginCommand("extractplugin"));
		$this->register("devmine", new MakePluginCommand("makeplugin"));
		$this->register("devmine", new MakeServerCommand("ms"));
		//$this->register("devmine", new MakeServerCommand("makeserver"));
		$this->register("devmine", new ExtractPluginCommand("ep"));
		$this->register("devmine", new MakePluginCommand("mp"));

		$this->register("devmine", new LoadPluginCommand("loadplugin"));

		$this->register("devmine", new LvdatCommand("lvdat"));
		$this->register("devmine", new BiomeCommand("biome"));
		$this->register("devmine", new CaveCommand("cave"));
		$this->register("devmine", new ChunkInfoCommand("chunkinfo"));

		$this->register("devmine", new VersionCommand("version"));
		$this->register("devmine", new FillCommand("fill"));
		$this->register("devmine", new PluginsCommand("plugins"));
		$this->register("devmine", new SeedCommand("seed"));
		$this->register("devmine", new HelpCommand("help"));
		$this->register("devmine", new StopCommand("stop"));
		$this->register("devmine", new TellCommand("tell"));
		$this->register("devmine", new DefaultGamemodeCommand("defaultgamemode"));
		$this->register("devmine", new BanCommand("ban"));
		$this->register("devmine", new BanIpCommand("ban-ip"));
		$this->register("devmine", new BanListCommand("banlist"));
		$this->register("devmine", new PardonCommand("pardon"));
		$this->register("devmine", new PardonIpCommand("pardon-ip"));
		$this->register("devmine", new SayCommand("say"));
		$this->register("devmine", new MeCommand("me"));
		$this->register("devmine", new ListCommand("list"));
		$this->register("devmine", new DifficultyCommand("difficulty"));
		$this->register("devmine", new KickCommand("kick"));
		$this->register("devmine", new OpCommand("op"));
		$this->register("devmine", new DeopCommand("deop"));
		$this->register("devmine", new WhitelistCommand("whitelist"));
		$this->register("devmine", new SaveOnCommand("save-on"));
		$this->register("devmine", new SaveOffCommand("save-off"));
		$this->register("devmine", new SaveCommand("save-all"));
		$this->register("devmine", new GiveCommand("give"));
		$this->register("devmine", new EffectCommand("effect"));
		$this->register("devmine", new EnchantCommand("enchant"));
		$this->register("devmine", new ParticleCommand("particle"));
		$this->register("devmine", new GamemodeCommand("gamemode"));
		$this->register("devmine", new KillCommand("kill"));
		$this->register("devmine", new SpawnpointCommand("spawnpoint"));
		$this->register("devmine", new SetWorldSpawnCommand("setworldspawn"));
		$this->register("devmine", new SummonCommand("summon"));
		$this->register("devmine", new TeleportCommand("tp"));
		$this->register("devmine", new TimeCommand("time"));
		$this->register("devmine", new TimingsCommand("timings"));
		$this->register("devmine", new ReloadCommand("reload"));
		$this->register("devmine", new XpCommand("xp"));
		$this->register("devmine", new SetBlockCommand("setblock"));

		if($this->server->getProperty("debug.commands", false)){
			$this->register("devmine", new StatusCommand("status"));
			$this->register("devmine", new GarbageCollectorCommand("gc"));
			$this->register("devmine", new DumpMemoryCommand("dumpmemory"));
		}
	}


	public function registerAll($fallbackPrefix, array $commands){
		foreach($commands as $command){
			$this->register($fallbackPrefix, $command);
		}
	}

	public function register($fallbackPrefix, Command $command, $label = null){
		if($label === null){
			$label = $command->getName();
		}
		$label = strtolower(trim($label));
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
			$this->server->getLogger()->critical($this->server->getLanguage()->translateString("devmine.command.exception", [$commandLine, (string) $target, $e->getMessage()]));
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
				$this->server->getLogger()->warning($this->server->getLanguage()->translateString("devmine.command.alias.illegal", [$alias]));
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
				$this->server->getLogger()->warning($this->server->getLanguage()->translateString("devmine.command.alias.notFound", [$alias, $bad]));
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
