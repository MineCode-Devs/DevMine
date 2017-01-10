<?php



namespace devmine\worlds\generator\normal\biome;


class SmallMountainsBiome extends MountainsBiome{

	public function __construct(){
		parent::__construct();

		$this->setElevation(63, 97);
	}

	public function getName(){
		return "Small Mountains";
	}
}