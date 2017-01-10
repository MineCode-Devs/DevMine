<?php

namespace devmine\events\player;
use devmine\events\Cancellable;
use devmine\creatures\player;
class PlayerToggleGlideEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;
	/** @var bool */
	protected $isGliding;
	public function __construct(Player $player, $isGliding){
		$this->player = $player;
		$this->isGliding = (bool) $isGliding;
	}
	public function isGliding(){
		return $this->isGliding;
	}
}
