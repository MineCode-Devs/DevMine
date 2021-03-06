<?php



namespace devmine\server\commands\defaults;


use devmine\server\commands\CommandSender;
use devmine\creatures\entities\Effect;
use devmine\creatures\entities\InstantEffect;
use devmine\events\TranslationContainer;
use devmine\inventory\items\enchantment\Enchantment;
use devmine\utilities\main\TextFormat;
use devmine\server\server;

class EnchantCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.enchant.description",
			"%commands.enchant.usage"
		);
		$this->setPermission("DevMine.command.enchant");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 2){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));
			return true;
		}

		$player = $sender->getServer()->getPlayer($args[0]);

		if($player === null){
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.player.notFound"));
			return true;
		}

		$enchantId = $args[1];
		$enchantLevel = isset($args[2]) ? (int) $args[2] : 1;

		$enchantment = Enchantment::getEnchantment($enchantId);
		if($enchantment->getId() === Enchantment::TYPE_INVALID){
			$enchantment = Enchantment::getEnchantmentByName($enchantId);
			if($enchantment->getId() === Enchantment::TYPE_INVALID){
	    		$sender->sendMessage(new TranslationContainer("commands.enchant.notFound", [$enchantment->getId()]));
				return true;
			}
		}
		$id = $enchantment->getId();
		$maxLevel = Enchantment::getEnchantMaxLevel($id);
		if($enchantLevel > $maxLevel or $enchantLevel <= 0){
			$sender->sendMessage(new TranslationContainer("commands.enchant.maxLevel", [$maxLevel]));
			return true;
		}
		$enchantment->setLevel($enchantLevel);

		$item = $player->getInventory()->getItemInHand();

		if($item->getId() <= 0){
			$sender->sendMessage(new TranslationContainer("commands.enchant.noItem"));
			return true;
		}
		
		if(Enchantment::getEnchantAbility($item) === 0){
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.enchant.cantEnchant"));
			return true;
		}

		$item->addEnchantment($enchantment);
		$player->getInventory()->setItemInHand($item);


		self::broadcastCommandMessage($sender, new TranslationContainer("%commands.enchant.success"));
		return true;
	}
}
