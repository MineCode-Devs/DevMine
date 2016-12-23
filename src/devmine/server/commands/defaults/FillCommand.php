<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\Command;
use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;
use devmine\Player;
use devmine\utilities\main\TextFormat;
use devmine\server\calculations\Vector3;
use devmine\inventory\items\ItemBlock;
use devmine\inventory\items\Item;
use devmine\levels\Level;

class FillCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.fill.description",
			"%commands.fill.usage"
		);
		$this->setPermission("devmine.command.fill");
	}

	public function execute(CommandSender $sender, $label, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		for($a = 0; $a < 6; $a++){
			if(isset($args[$a])){
				if(is_numeric($args[$a]) and is_integer($args[$a] + 0)){
					$item = Item::fromString($args[6]);
					if($item instanceof ItemBlock){
						$xmin = min($args[0] + 0, $args[3] + 0);
						$xmax = max($args[0] + 0, $args[3] + 0);
						$ymin = min($args[1] + 0, $args[4] + 0);
						$ymax = max($args[1] + 0, $args[4] + 0);
						$zmin = min($args[2] + 0, $args[5] + 0);
						$zmax = max($args[2] + 0, $args[5] + 0);
						$level = ($sender instanceof Player) ? $sender->getLevel() : $sender->getServer()->getDefaultLevel();
						$n = 0;
						$nmax = ($xmax - $xmin + 1) * ($ymax - $ymin + 1) * ($zmax - $zmin + 1);
						for($x = $xmin; $x <= $xmax; $x++){
							for($y = $ymin; $y <= $ymax; $y++){
								for($z = $zmin; $z <= $zmax; $z++){
									if ($this->setBlock(new Vector3($x, $y, $z), $level, $item, isset($args[7]) ? $args[7] : 0)) {
										$n++;
										if (is_int($n/10000)) {
											$sender->sendMessage(new TranslationContainer("$n out of $nmax blocks filled, now at $x $y $z", []));
										}
									}
									else {
										$sender->sendMessage(TextFormat::RED . new TranslationContainer("Error after filling $n out of $nmax blocks.", []));
										return false;
									}
								}
							}
						}
						$sender->sendMessage(new TranslationContainer("Total of $n blocks filled.", []));
						return true;
					}
					$sender->sendMessage(TextFormat::RED . new TranslationContainer($args[6] . " is not a valid block.", []));
					return false;
				}
					$sender->sendMessage(TextFormat::RED . new TranslationContainer($args[$a] . " is not a valid coordinate.", []));
					$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
				return false;
			}
			$sender->sendMessage(TextFormat::RED . new TranslationContainer("devmine.command.fill.missingParameter", []));
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
			return false;
		}
	}

	private function setBlock(Vector3 $p, Level $lvl, ItemBlock $b, int $meta = 0) : bool{
		$block = $b->getBlock();
		$block->setDamage($meta);
		$lvl->setBlock($p, $block);
		return true;
	}
}
