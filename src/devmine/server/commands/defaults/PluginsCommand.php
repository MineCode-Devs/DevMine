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
use devmine\events\TranslationContainer;
use devmine\utilities\main\TextFormat;

class PluginsCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.plugins.description",
			"%DevMine.command.plugins.usage",
			["pl"]
		);
		$this->setPermission("DevMine.command.plugins");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
		$this->sendPluginList($sender);
		return true;
	}

	private function sendPluginList(CommandSender $sender){
		$list = "";
		foreach(($plugins = $sender->getServer()->getPluginManager()->getPlugins()) as $plugin){
			if(strlen($list) > 0){
				$list .= TextFormat::WHITE . ", ";
			}
			$list .= $plugin->isEnabled() ? TextFormat::GREEN : TextFormat::RED;
			$list .= $plugin->getDescription()->getFullName();
		}

		$sender->sendMessage(new TranslationContainer("DevMine.command.plugins.success", [count($plugins), $list]));
	}
}
