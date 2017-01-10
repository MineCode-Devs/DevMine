<?php



namespace devmine\events\player;

use devmine\events\Cancellable;
use devmine\creatures\player;

/**
 * Called when a player does an animation
 */
class PlayerAnimationEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	const ARM_SWING = 1;
	const WAKE_UP = 3;

	private $animationType;

	/**
	 * @param Player $player
	 * @param int    $animation
	 */
	public function __construct(Player $player, $animation = self::ARM_SWING){
		$this->player = $player;
		$this->animationType = $animation;
	}

	/**
	 * @return int
	 */
	public function getAnimationType(){
		return $this->animationType;
	}

}
