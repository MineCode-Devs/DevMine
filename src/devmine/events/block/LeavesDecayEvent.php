<?php



namespace devmine\events\block;

use devmine\inventory\blocks\Block;
use devmine\events\Cancellable;

class LeavesDecayEvent extends BlockEvent implements Cancellable{
	public static $handlerList = null;

	public function __construct(Block $block){
		parent::__construct($block);
	}

}