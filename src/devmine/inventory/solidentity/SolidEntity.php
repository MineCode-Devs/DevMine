<?php



/**
 * All the solidentity classes and related classes
 */
namespace devmine\inventory\solidentity;

use devmine\server\events\Timings;
use devmine\levels\format\Chunk;
use devmine\levels\format\FullChunk;
use devmine\levels\Level;
use devmine\levels\Position;
use devmine\creatures\player\tag\CompoundTag;
use devmine\creatures\player\tag\IntTag;
use devmine\creatures\player\tag\StringTag;
use devmine\utilities\main\ChunkException;

abstract class solidentity extends Position{
	const SIGN = "Sign";
	const CHEST = "Chest";
	const FURNACE = "Furnace";
	const FLOWER_POT = "FlowerPot";
	const MOB_SPAWNER = "MobSpawner";
	const SKULL = "Skull";
	const BREWING_STAND = "BrewingStand";
	const ENCHANT_TABLE = "EnchantTable";
	const ITEM_FRAME = "ItemFrame";
	const DISPENSER = "Dispenser";
	const DROPPER = "Dropper";
	const DAY_LIGHT_DETECTOR = "DLDetector";
	const CAULDRON = "Cauldron";

	public static $solidentityCount = 1;

	private static $knownsolidentities = [];
	private static $shortNames = [];

	/** @var Chunk */
	public $chunk;
	public $name;
	public $id;
	public $x;
	public $y;
	public $z;
	public $attach;
	public $epilogos;
	public $closed = false;
	public $namedtag;
	protected $lastUpdate;
	protected $server;
	protected $timings;

	/** @var \devmine\server\events\TimingsHandler */
	public $tickTimer;

	/**
	 * @param string    $type
	 * @param FullChunk $chunk
	 * @param CompoundTag  $nbt
	 * @param           $args
	 *
	 * @return solidentity
	 */
	public static function createsolidentity($type, FullChunk $chunk, CompoundTag $nbt, ...$args){
		if(isset(self::$knownsolidentities[$type])){
			$class = self::$knownsolidentities[$type];
			return new $class($chunk, $nbt, ...$args);
		}

		return null;
	}

	/**
	 * @param $className
	 *
	 * @return bool
	 */
	public static function registersolidentity($className){
		$class = new \ReflectionClass($className);
		if(is_a($className, solidentity::class, true) and !$class->isAbstract()){
			self::$knownsolidentities[$class->getShortName()] = $className;
			self::$shortNames[$className] = $class->getShortName();
			return true;
		}

		return false;
	}

	/**
	 * Returns the short save name
	 *
	 * @return string
	 */
	public function getSaveId(){
		return self::$shortNames[static::class];
	}

	public function __construct(FullChunk $chunk, CompoundTag $nbt){
		if($chunk === null or $chunk->getProvider() === null){
			throw new ChunkException("Invalid garbage Chunk given to solidentity");
		}

		$this->timings = Timings::getsolidentityEntityTimings($this);

		$this->server = $chunk->getProvider()->getLevel()->getServer();
		$this->chunk = $chunk;
		$this->setLevel($chunk->getProvider()->getLevel());
		$this->namedtag = $nbt;
		$this->name = "";
		$this->lastUpdate = microtime(true);
		$this->id = solidentity::$solidentityCount++;
		$this->x = (int) $this->namedtag["x"];
		$this->y = (int) $this->namedtag["y"];
		$this->z = (int) $this->namedtag["z"];

		$this->chunk->addsolidentity($this);
		$this->getLevel()->addsolidentity($this);
		$this->tickTimer = Timings::getsolidentityEntityTimings($this);
	}

	public function getId(){
		return $this->id;
	}

	public function saveNBT(){
		$this->namedtag->id = new StringTag("id", $this->getSaveId());
		$this->namedtag->x = new IntTag("x", $this->x);
		$this->namedtag->y = new IntTag("y", $this->y);
		$this->namedtag->z = new IntTag("z", $this->z);
	}

	/**
	 * @return \devmine\inventory\blocks\Block
	 */
	public function getBlock(){
		return $this->level->getBlock($this);
	}

	public function onUpdate(){
		return false;
	}

	public final function scheduleUpdate(){
		$this->level->updatesolidentities[$this->id] = $this;
	}

	public function __destruct(){
		$this->close();
	}

	public function close(){
		if(!$this->closed){
			$this->closed = true;
			unset($this->level->updatesolidentities[$this->id]);
			if($this->chunk instanceof FullChunk){
				$this->chunk->removesolidentity($this);
			}
			if(($level = $this->getLevel()) instanceof Level){
				$level->removesolidentity($this);
			}
			$this->level = null;
		}
	}

	public function getName() : string{
		return $this->name;
	}

}
