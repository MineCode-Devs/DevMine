<?php



namespace devmine\events\block;

use devmine\inventory\blocks\Block;
use devmine\events\Cancellable;

class BlockSpreadEvent extends BlockFormEvent implements Cancellable{
	public static $handlerList = null;

	/** @var Block */
	private $source;

	public function __construct(Block $block, Block $source, Block $newState){
		parent::__construct($block, $newState);
		$this->source = $source;
	}

	/**
	 * @return Block
	 */
	public function getSource(){
		return $this->source;
	}

}