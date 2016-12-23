<?php



namespace devmine\levels\generator;


use devmine\levels\Level;

use devmine\server\tasks\AsyncTask;


class GeneratorUnregisterTask extends AsyncTask{

	public $levelId;

	public function __construct(Level $level){
		$this->levelId = $level->getId();
	}

	public function onRun(){
		$this->saveToThreadStore("generation.level{$this->levelId}.manager", null);
		$this->saveToThreadStore("generation.level{$this->levelId}.generator", null);
	}
}
