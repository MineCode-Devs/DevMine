<?php



namespace devmine\events\player;

use devmine\events\Cancellable;
use devmine\creatures\player;

/**
 * Called when a player is awarded an achievement
 */
class PlayerAchievementAwardedEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	/** @var string */
	protected $achievement;

	/**
	 * @param Player $player
	 * @param string $achievementId
	 */
	public function __construct(Player $player, $achievementId){
		$this->player = $player;
		$this->achievement = $achievementId;
	}

	public function getAchievement(){
		return $this->achievement;
	}
}