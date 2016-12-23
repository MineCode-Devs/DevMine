<?php



namespace devmine\levels\generator;

use devmine\inventory\blocks\Block;

use devmine\levels\generator\biome\Biome;
use devmine\levels\Level;
use devmine\levels\SimpleChunkManager;
use devmine\server\tasks\AsyncTask;

use devmine\utilities\main\Random;

class GeneratorRegisterTask extends AsyncTask{

	public $generator;
	public $settings;
	public $seed;
	public $levelId;
	public $waterHeight;

	public function __construct(Level $level, Generator $generator){
		$this->generator = get_class($generator);
		$this->waterHeight = $generator->getWaterHeight();
		$this->settings = serialize($generator->getSettings());
		$this->seed = $level->getSeed();
		$this->levelId = $level->getId();
	}

	public function onRun(){
		Block::init();
		Biome::init();
		$manager = new SimpleChunkManager($this->seed, $this->waterHeight);
		$this->saveToThreadStore("generation.level{$this->levelId}.manager", $manager);
		/** @var Generator $generator */
		$generator = $this->generator;
		$generator = new $generator(unserialize($this->settings));
		$generator->init($manager, new Random($manager->getSeed()));
		$this->saveToThreadStore("generation.level{$this->levelId}.generator", $generator);
	}
}
