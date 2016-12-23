<?php
namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\utilities\main\TextFormat;

class LoadPluginCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"Load a plugin",
			"/loadplugin <file name or folder name>"
		);
		$this->setPermission("devmine.command.loadplugin");
	}

	public function execute(CommandSender $sender, $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return false;
		}

		if(count($args) === 0){
			$sender->sendMessage(TextFormat::RED . "Usage: " . $this->usageMessage);
			return true;
		}

		if(!isset($args[0])) return false;

		$plugin = $sender->getServer()->getPluginManager()->loadPlugin($sender->getServer()->getPluginPath() . DIRECTORY_SEPARATOR . $args[0]);
		if($plugin != null){
			$sender->getServer()->getPluginManager()->enablePlugin($plugin);
			return true;
		}
		return false;
	}
}