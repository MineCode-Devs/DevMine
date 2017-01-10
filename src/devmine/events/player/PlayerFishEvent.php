<?php



namespace devmine\events\player;

use devmine\creatures\entities\FishingHook;
use devmine\events\Cancellable;
use devmine\inventory\items\Item;
use devmine\creatures\player;

/**
 * Called when a player uses the fishing rod
 */
class PlayerFishEvent extends PlayerEvent implements Cancellable{

	public static $handlerList = null;

	/** @var Item */
	private $item;

	/** @var FishingHook */
	private $hook;

	/**
	 * @param Player $player
	 * @param Item   $item
	 * @param        $fishingHook
	 */
	public function __construct(Player $player, Item $item, $fishingHook = null){
		$this->player = $player;
		$this->item = $item;
		$this->hook = $fishingHook;
	}

	/**
	 * @return Item
	 */
	public function getItem(){
		return clone $this->item;
	}

	public function getHook(){
		return $this->hook;
	}
}
