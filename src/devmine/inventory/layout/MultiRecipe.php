<?php



namespace devmine\inventory\layout;

use devmine\utilities\main\UUID;

class MultiRecipe{

	private $uuid;

	public function __construct(UUID $uuid){
		$this->uuid = $uuid;
	}

}