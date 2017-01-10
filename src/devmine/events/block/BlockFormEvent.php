<?php



namespace devmine\events\block;

use devmine\inventory\blocks\Block;
use devmine\events\Cancellable;

class BlockFormEvent extends BlockGrowEvent implements Cancellable{
	public static $handlerList = null;

	public function __construct(Block $block, Block $newState){
		parent::__construct($block, $newState);
	}

}