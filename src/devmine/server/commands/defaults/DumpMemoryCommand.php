<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\server\commands\Command;


class DumpMemoryCommand extends VanillaCommand{
	
	public function __construct($name){
		parent::__construct(
			$name,
			"Dumps the memory",
			"/$name [path]"
		);
		$this->setPermission("DevMine.command.dumpmemory");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}
		
		Command::broadcastCommandMessage($sender, "Dumping server memory");
		
		$sender->getServer()->getMemoryManager()->dumpServerMemory(isset($args[0]) ? $args[0] : $sender->getServer()->getDataPath() . "/memory_dumps/memoryDump_".date("D_M_j-H.i.s-T_Y", time()), 48, 80);
		return true;
	}
	
}
