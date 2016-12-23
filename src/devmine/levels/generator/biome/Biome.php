<?php



namespace devmine\levels\generator\biome;

use devmine\inventory\blocks\Block;
use devmine\levels\ChunkManager;
use devmine\levels\generator\normal\biome\SwampBiome;
use devmine\levels\generator\normal\biome\DesertBiome;
use devmine\levels\generator\normal\biome\ForestBiome;
use devmine\levels\generator\normal\biome\IcePlainsBiome;
use devmine\levels\generator\normal\biome\MountainsBiome;
use devmine\levels\generator\normal\biome\OceanBiome;
use devmine\levels\generator\normal\biome\PlainBiome;
use devmine\levels\generator\normal\biome\RiverBiome;
use devmine\levels\generator\normal\biome\SmallMountainsBiome;
use devmine\levels\generator\normal\biome\TaigaBiome;
use devmine\levels\generator\hell\HellBiome;
use devmine\levels\generator\populator\Populator;
use devmine\utilities\main\Random;

use devmine\levels\generator\populator\Flower;

abstract class Biome{

	const OCEAN = 0;
	const PLAINS = 1;
	const DESERT = 2;
	const MOUNTAINS = 3;
	const FOREST = 4;
	const TAIGA = 5;
	const SWAMP = 6;
	const RIVER = 7;

	const HELL = 8;

	const ICE_PLAINS = 12;


	const SMALL_MOUNTAINS = 20;


	const BIRCH_FOREST = 27;


	const MAX_BIOMES = 256;

	/** @var Biome[] */
	private static $biomes = [];

	private $id;
	private $registered = false;
	/** @var Populator[] */
	private $populators = [];

	private $minElevation;
	private $maxElevation;

	private $groundCover = [];

	protected $rainfall = 0.5;
	protected $temperature = 0.5;
	protected $grassColor = 0;

	protected static function register($id, Biome $biome){
		self::$biomes[(int) $id] = $biome;
		$biome->setId((int) $id);
		$biome->grassColor = self::generateBiomeColor($biome->getTemperature(), $biome->getRainfall());

		$flowerPopFound = false;

		foreach($biome->getPopulators() as $populator){
			if($populator instanceof Flower){
				$flowerPopFound = true;
				break;
			}
		}

		if($flowerPopFound === false){
			$flower = new Flower();
			$biome->addPopulator($flower);
		}
	}

	public static function init(){
		self::register(self::OCEAN, new OceanBiome());
		self::register(self::PLAINS, new PlainBiome());
		self::register(self::DESERT, new DesertBiome());
		self::register(self::MOUNTAINS, new MountainsBiome());
		self::register(self::FOREST, new ForestBiome());
		self::register(self::TAIGA, new TaigaBiome());
		self::register(self::SWAMP, new SwampBiome());
		self::register(self::RIVER, new RiverBiome());

		self::register(self::ICE_PLAINS, new IcePlainsBiome());


		self::register(self::SMALL_MOUNTAINS, new SmallMountainsBiome());
		self::register(self::HELL, new HellBiome());

		self::register(self::BIRCH_FOREST, new ForestBiome(ForestBiome::TYPE_BIRCH));
	}

	/**
	 * @param $id
	 *
	 * @return Biome
	 */
	public static function getBiome($id){
		return isset(self::$biomes[$id]) ? self::$biomes[$id] : self::$biomes[self::OCEAN];
	}

	public function clearPopulators(){
		$this->populators = [];
	}

	public function addPopulator(Populator $populator){
		$this->populators[] = $populator;
	}

	public function populateChunk(ChunkManager $level, $chunkX, $chunkZ, Random $random){
		foreach($this->populators as $populator){
			$populator->populate($level, $chunkX, $chunkZ, $random);
		}
	}

	public function getPopulators(){
		return $this->populators;
	}

	public function setId($id){
		if(!$this->registered){
			$this->registered = true;
			$this->id = $id;
		}
	}

	public function getId(){
		return $this->id;
	}

	public abstract function getName();

	public function getMinElevation(){
		return $this->minElevation;
	}

	public function getMaxElevation(){
		return $this->maxElevation;
	}

	public function setElevation($min, $max){
		$this->minElevation = $min;
		$this->maxElevation = $max;
	}

	/**
	 * @return Block[]
	 */
	public function getGroundCover(){
		return $this->groundCover;
	}

	/**
	 * @param Block[] $covers
	 */
	public function setGroundCover(array $covers){
		$this->groundCover = $covers;
	}

	public function getTemperature(){
		return $this->temperature;
	}

	public function getRainfall(){
		return $this->rainfall;
	}

	private static function generateBiomeColor($temperature, $rainfall){
		$x = (1 - $temperature) * 255;
		$z = (1 - $rainfall * $temperature) * 255;
		$c = self::interpolateColor(256, $x, $z, [0x47, 0xd0, 0x33], [0x6c, 0xb4, 0x93], [0xbf, 0xb6, 0x55], [0x80, 0xb4, 0x97]);
		return ((int) ($c[0] << 16)) | (int) (($c[1] << 8)) | (int) ($c[2]);
	}


	private static function interpolateColor($size, $x, $z, $c1, $c2, $c3, $c4){
		$l1 = self::lerpColor($c1, $c2, $x / $size);
		$l2 = self::lerpColor($c3, $c4, $x / $size);

		return self::lerpColor($l1, $l2, $z / $size);
	}

	private static function lerpColor($a, $b, $s){
		$invs = 1 - $s;
		return [$a[0] * $invs + $b[0] * $s, $a[1] * $invs + $b[1] * $s, $a[2] * $invs + $b[2] * $s];
	}


	/**
	 * @return int (Red|Green|Blue)
	 */
	abstract public function getColor();
}