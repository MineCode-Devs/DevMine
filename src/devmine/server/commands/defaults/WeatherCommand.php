<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;
use devmine\levels\Level;
use devmine\levels\weather\Weather;
use devmine\Player;
use devmine\utilities\main\TextFormat;

class WeatherCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.weather.description",
			"%devmine.command.weather.usage"
		);
		$this->setPermission("devmine.command.weather");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 1){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return false;
		}

		if($sender instanceof Player){
			$wea = Weather::getWeatherFromString($args[0]);
			if(!isset($args[1])) $duration = mt_rand(min($sender->getServer()->weatherRandomDurationMin, $sender->getServer()->weatherRandomDurationMax), max($sender->getServer()->weatherRandomDurationMin, $sender->getServer()->weatherRandomDurationMax));
			else $duration = (int) $args[1];
			if($wea >= 0 and $wea <= 3){
				$sender->getLevel()->getWeather()->setWeather($wea, $duration);
				$sender->sendMessage(new TranslationContainer("devmine.command.weather.changed", [$sender->getLevel()->getFolderName()]));
				return true;
				/*if(WeatherManager::isRegistered($sender->getLevel())){
					$sender->getLevel()->getWeather()->setWeather($wea, $duration);
					$sender->sendMessage(new TranslationContainer("devmine.command.weather.changed", [$sender->getLevel()->getFolderName()]));
					return true;
				}else{
					$sender->sendMessage(new TranslationContainer("devmine.command.weather.noregistered", [$sender->getLevel()->getFolderName()]));
					return false;
				}*/
			}else{
				$sender->sendMessage(TextFormat::RED . "%devmine.command.weather.invalid");
				return false;
			}
		}

		if(count($args) < 2){
			$sender->sendMessage(TextFormat::RED . "%devmine.command.weather.wrong");
			return false;
		}

		$level = $sender->getServer()->getLevelByName($args[0]);
		if(!$level instanceof Level){
			$sender->sendMessage(TextFormat::RED . "%devmine.command.weather.invalid.level");
			return false;
		}

		$wea = Weather::getWeatherFromString($args[1]);
		if(!isset($args[1])) $duration = mt_rand(min($sender->getServer()->weatherRandomDurationMin, $sender->getServer()->weatherRandomDurationMax), max($sender->getServer()->weatherRandomDurationMin, $sender->getServer()->weatherRandomDurationMax));
		else $duration = (int) $args[1];
		if($wea >= 0 and $wea <= 3){
			$level->getWeather()->setWeather($wea, $duration);
			$sender->sendMessage(new TranslationContainer("devmine.command.weather.changed", [$level->getFolderName()]));
			return true;
			/*if(WeatherManager::isRegistered($level)){
				$level->getWeather()->setWeather($wea, $duration);
				$sender->sendMessage(new TranslationContainer("devmine.command.weather.changed", [$level->getFolderName()]));
				return true;
			}else{
				$sender->sendMessage(new TranslationContainer("devmine.command.weather.noregistered", [$level->getFolderName()]));
				return false;
			}*/
		}else{
			$sender->sendMessage(TextFormat::RED . "%devmine.command.weather.invalid");
			return false;
		}
	}
}
