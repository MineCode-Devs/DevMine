<?php



namespace devmine\worlds\generator;

use devmine\inventory\blocks\CoalOre;
use devmine\inventory\blocks\DiamondOre;
use devmine\inventory\blocks\Dirt;
use devmine\inventory\blocks\GoldOre;
use devmine\inventory\blocks\Gravel;
use devmine\inventory\blocks\IronOre;
use devmine\inventory\blocks\LapisOre;
use devmine\inventory\blocks\RedstoneOre;
use devmine\inventory\items\Item;
use devmine\worlds\ChunkManager;
use devmine\worlds\format\Chunk;
use devmine\worlds\generator\populator\Ore;
use devmine\worlds\generator\populator\Populator;
use devmine\server\calculations\Vector3;
use devmine\utilities\main\Random;

class Flat extends Generator{
	/** @var ChunkManager */
	private $level;
	/** @var Chunk */
	private $chunk;
	/** @var Random */
	private $random;
	/** @var Populator[] */
	private $populators = [];
	private $structure, $chunks, $options, $floorLevel, $preset;

	public function getSettings(){
		return $this->options;
	}

	public function getName(){
		return "flat";
	}

	public function __construct(array $options = []){
		$this->preset = "2;7,2x3,2;1;";
		//$this->preset = "2;7,59x1,3x3,2;1;spawn(radius=10 block=89),decoration(treecount=80 grasscount=45)";
		$this->options = $options;
		$this->chunk = null;

		if(isset($this->options["decoration"])){
			$ores = new Ore();
			$ores->setOreTypes([
				new object\OreType(new CoalOre(), 20, 16, 0, 128),
				new object\OreType(new IronOre(), 20, 8, 0, 64),
				new object\OreType(new RedstoneOre(), 8, 7, 0, 16),
				new object\OreType(new LapisOre(), 1, 6, 0, 32),
				new object\OreType(new GoldOre(), 2, 8, 0, 32),
				new object\OreType(new DiamondOre(), 1, 7, 0, 16),
				new object\OreType(new Dirt(), 20, 32, 0, 128),
				new object\OreType(new Gravel(), 10, 16, 0, 128),
			]);
			$this->populators[] = $ores;
		}

		/*if(isset($this->options["mineshaft"])){
			$this->populators[] = new MineshaftPopulator(isset($this->options["mineshaft"]["chance"]) ? floatval($this->options["mineshaft"]["chance"]) : 0.01);
		}*/
	}

	protected function parsePreset($preset, $chunkX, $chunkZ){
		$this->preset = $preset;
		$preset = explode(";", $preset);
		$version = (int) $preset[0];
		$blocks = $preset[1] ?? "";
		$biome = $preset[2] ?? 1;
		$options = $preset[3] ?? "";
		preg_match_all('#^(([0-9]*x|)([0-9]{1,3})(|:[0-9]{0,2}))$#m', str_replace(",", "\n", $blocks), $matches);
		$y = 0;
		$this->structure = [];
		$this->chunks = [];
		foreach($matches[3] as $i => $b){
			$b = Item::fromString($b . $matches[4][$i]);
			$cnt = $matches[2][$i] === "" ? 1 : intval($matches[2][$i]);
			for($cY = $y, $y += $cnt; $cY < $y; ++$cY){
				$this->structure[$cY] = [$b->getId(), $b->getDamage()];
			}
		}

		$this->floorLevel = $y;

		for(; $y < 0xFF; ++$y){
			$this->structure[$y] = [0, 0];
		}


		$this->chunk = clone $this->level->getChunk($chunkX, $chunkZ);
		$this->chunk->setGenerated();

		for($Z = 0; $Z < 16; ++$Z){
			for($X = 0; $X < 16; ++$X){
				$this->chunk->setBiomeId($X, $Z, $biome);
				for($y = 0; $y < 128; ++$y){
					$this->chunk->setBlock($X, $y, $Z, ...$this->structure[$y]);
				}
			}
		}


		preg_match_all('#(([0-9a-z_]{1,})\(?([0-9a-z_ =:]{0,})\)?),?#', $options, $matches);
		foreach($matches[2] as $i => $option){
			$params = true;
			if($matches[3][$i] !== ""){
				$params = [];
				$p = explode(" ", $matches[3][$i]);
				foreach($p as $k){
					$k = explode("=", $k);
					if(isset($k[1])){
						$params[$k[0]] = $k[1];
					}
				}
			}
			$this->options[$option] = $params;
		}
	}

	public function init(ChunkManager $level, Random $random){
		$this->level = $level;
		$this->random = $random;

		/*
		  // Commented out : We want to delay this
		if(isset($this->options["preset"]) and $this->options["preset"] != ""){
			$this->parsePreset($this->options["preset"]);
		}else{
			$this->parsePreset($this->preset);
		}
		*/
	}

	public function generateChunk($chunkX, $chunkZ){
		if($this->chunk === null){
			if(isset($this->options["preset"]) and $this->options["preset"] != ""){
				$this->parsePreset($this->options["preset"], $chunkX, $chunkZ);
			}else{
				$this->parsePreset($this->preset, $chunkX, $chunkZ);
			}
		}
		$chunk = clone $this->chunk;
		$chunk->setX($chunkX);
		$chunk->setZ($chunkZ);
		$this->level->setChunk($chunkX, $chunkZ, $chunk);
	}

	public function populateChunk($chunkX, $chunkZ){
		$this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
		foreach($this->populators as $populator){
			$populator->populate($this->level, $chunkX, $chunkZ, $this->random);
		}

	}

	public function getSpawn(){
		return new Vector3(128, $this->floorLevel, 128);
	}
}
