<?php



namespace devmine\events\block;

use devmine\inventory\blocks\Block;
use devmine\events\Cancellable;

class BlockGrowEvent extends BlockEvent implements Cancellable{
	public static $handlerList = null;

	/** @var Block */
	private $newState;

	public function __construct(Block $block, Block $newState){
		parent::__construct($block);
		$this->newState = $newState;
	}

	/**
	 * @return Block
	 */
	public function getNewState(){
		return $this->newState;
	}

}