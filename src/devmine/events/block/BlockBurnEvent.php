<?php



namespace devmine\server\events\block;

use devmine\server\events\Cancellable;

class BlockBurnEvent extends BlockEvent implements Cancellable{
	public static $handlerList = null;
}