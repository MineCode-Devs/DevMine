<?php



namespace devmine\server\commands\defaults;

use devmine\inventory\blocks\Block;
use devmine\server\commands\CommandSender;
use devmine\server\events\TranslationContainer;
use devmine\inventory\items\Item;
use devmine\levels\particle\AngryVillagerParticle;
use devmine\levels\particle\BubbleParticle;
use devmine\levels\particle\CriticalParticle;
use devmine\levels\particle\DustParticle;
use devmine\levels\particle\EnchantmentTableParticle;
use devmine\levels\particle\EnchantParticle;
use devmine\levels\particle\ExplodeParticle;
use devmine\levels\particle\FlameParticle;
use devmine\levels\particle\HappyVillagerParticle;
use devmine\levels\particle\HeartParticle;
use devmine\levels\particle\HugeExplodeParticle;
use devmine\levels\particle\InkParticle;
use devmine\levels\particle\InstantEnchantParticle;
use devmine\levels\particle\ItemBreakParticle;
use devmine\levels\particle\LargeExplodeParticle;
use devmine\levels\particle\LavaDripParticle;
use devmine\levels\particle\LavaParticle;
use devmine\levels\particle\Particle;
use devmine\levels\particle\PortalParticle;
use devmine\levels\particle\RainSplashParticle;
use devmine\levels\particle\RedstoneParticle;
use devmine\levels\particle\SmokeParticle;
use devmine\levels\particle\SplashParticle;
use devmine\levels\particle\SporeParticle;
use devmine\levels\particle\TerrainParticle;
use devmine\levels\particle\WaterDripParticle;
use devmine\levels\particle\WaterParticle;
use devmine\server\calculations\Vector3;
use devmine\Player;
use devmine\utilities\main\Random;
use devmine\utilities\main\TextFormat;

class ParticleCommand extends VanillaCommand{

	public function __construct($name){
		parent::__construct(
			$name,
			"%devmine.command.particle.description",
			"%devmine.command.particle.usage"
		);
		$this->setPermission("devmine.command.particle");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) < 7){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return true;
		}

		if($sender instanceof Player){
			$level = $sender->getLevel();
		}else{
			$level = $sender->getServer()->getDefaultLevel();
		}

		$name = strtolower($args[0]);

		$pos = new Vector3((float) $args[1], (float) $args[2], (float) $args[3]);

		$xd = (float) $args[4];
		$yd = (float) $args[5];
		$zd = (float) $args[6];

		$count = isset($args[7]) ? max(1, (int) $args[7]) : 1;

		$data = isset($args[8]) ? (int) $args[8] : null;

		$particle = $this->getParticle($name, $pos, $xd, $yd, $zd, $data);

		if($particle === null){
			$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.particle.notFound", [$name]));
			return true;
		}


		$sender->sendMessage(new TranslationContainer("commands.particle.success", [$name, $count]));

		$random = new Random((int) (microtime(true) * 1000) + mt_rand());

		for($i = 0; $i < $count; ++$i){
			$particle->setComponents(
				$pos->x + $random->nextSignedFloat() * $xd,
				$pos->y + $random->nextSignedFloat() * $yd,
				$pos->z + $random->nextSignedFloat() * $zd
			);
			$level->addParticle($particle);
		}

		return true;
	}


	/**
	 * @param         $name
	 * @param Vector3 $pos
	 * @param         $xd
	 * @param         $yd
	 * @param         $zd
	 * @param         $data
	 * @return null|DustParticle|ItemBreakParticle|TerrainParticle
	 */
	private function getParticle($name, Vector3 $pos, $xd, $yd, $zd, $data){
		switch($name){
			case "explode":
				return new ExplodeParticle($pos);
			case "largeexplode":
				return new LargeExplodeParticle($pos);
			case "hugeexplosion":
				return new HugeExplodeParticle($pos);
			case "bubble":
				return new BubbleParticle($pos);
			case "splash":
				return new SplashParticle($pos);
			case "wake":
			case "water":
				return new WaterParticle($pos);
			case "crit":
				return new CriticalParticle($pos);
			case "smoke":
				return new SmokeParticle($pos, $data !== null ? $data : 0);
			case "spell":
				return new EnchantParticle($pos);
			case "instantspell":
				return new InstantEnchantParticle($pos);
			case "dripwater":
				return new WaterDripParticle($pos);
			case "driplava":
				return new LavaDripParticle($pos);
			case "townaura":
			case "spore":
				return new SporeParticle($pos);
			case "portal":
				return new PortalParticle($pos);
			case "flame":
				return new FlameParticle($pos);
			case "lava":
				return new LavaParticle($pos);
			case "reddust":
				return new RedstoneParticle($pos, $data !== null ? $data : 1);
			case "snowballpoof":
				return new ItemBreakParticle($pos, Item::get(Item::SNOWBALL));
			case "slime":
				return new ItemBreakParticle($pos, Item::get(Item::SLIMEBALL));
			case "itembreak":
				if($data !== null and $data !== 0){
					return new ItemBreakParticle($pos, $data);
				}
				break;
			case "terrain":
				if($data !== null and $data !== 0){
					return new TerrainParticle($pos, $data);
				}
				break;
			case "heart":
				return new HeartParticle($pos, $data !== null ? $data : 0);
			case "ink":
				return new InkParticle($pos, $data !== null ? $data : 0);
			case "droplet":
				return new RainSplashParticle($pos);
			case "enchantmenttable":
				return new EnchantmentTableParticle($pos);
			case "happyvillager":
				return new HappyVillagerParticle($pos);
			case "angryvillager":
				return new AngryVillagerParticle($pos);

		}

		if(substr($name, 0, 10) === "iconcrack_"){
			$d = explode("_", $name);
			if(count($d) === 3){
				return new ItemBreakParticle($pos, Item::get((int) $d[1], (int) $d[2]));
			}
		}elseif(substr($name, 0, 11) === "blockcrack_"){
			$d = explode("_", $name);
			if(count($d) === 2){
				return new TerrainParticle($pos, Block::get($d[1] & 0xff, $d[1] >> 12));
			}
		}elseif(substr($name, 0, 10) === "blockdust_"){
			$d = explode("_", $name);
			if(count($d) >= 4){
				return new DustParticle($pos, $d[1] & 0xff, $d[2] & 0xff, $d[3] & 0xff, isset($d[4]) ? $d[4] & 0xff : 255);
			}
		}

		return null;
	}
}