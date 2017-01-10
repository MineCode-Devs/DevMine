<?php



namespace devmine\events\player;

use devmine\inventory\blocks\Block;
use devmine\events\Cancellable;
use devmine\inventory\items\Item;
use devmine\worlds\Position;
use devmine\server\calculations\Vector3;
use devmine\creatures\player;

/**
 * Called when a player interacts or touches a block (including air?)
 */
class PlayerInteractEvent extends PlayerEvent implements Cancellable{
	public static $handlerList = null;

	const LEFT_CLICK_BLOCK = 0;
	const RIGHT_CLICK_BLOCK = 1;
	const LEFT_CLICK_AIR = 2;
	const RIGHT_CLICK_AIR = 3;
	const PHYSICAL = 4;

	/**
	 * @var \devmine\inventory\blocks\Block;
	 */
	protected $blockTouched;

	protected $touchVector;

	/** @var int */
	protected $blockFace;

	/** @var \devmine\inventory\items\Item */
	protected $item;
	
	protected $action;

	public function __construct(Player $player, Item $item, Vector3 $block, $face, $action = PlayerInteractEvent::RIGHT_CLICK_BLOCK){
		if($block instanceof Block){
			$this->blockTouched = $block;
			$this->touchVector = new Vector3(0, 0, 0);
		}else{
			$this->touchVector = $block;
			$this->blockTouched = Block::get(0, 0, new Position(0, 0, 0, $player->level));
		}
		$this->player = $player;
		$this->item = $item;
		$this->blockFace = (int) $face;
		$this->action = (int) $action;
	}

	/**
	 * @return int
	 */
	public function getAction(){
		return $this->action;
	}

	/**
	 * @return Item
	 */
	public function getItem(){
		return $this->item;
	}

	/**
	 * @return Block
	 */
	public function getBlock(){
		return $this->blockTouched;
	}

	/**
	 * @return Vector3
	 */
	public function getTouchVector(){
		return $this->touchVector;
	}

	/**
	 * @return int
	 */
	public function getFace(){
		return $this->blockFace;
	}
}
