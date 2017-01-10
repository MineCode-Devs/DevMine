<?php

 
namespace devmine\events\server;

use devmine\events;
use devmine\events\Cancellable;

class ServerShutdownEvent extends ServerEvent implements Cancellable{
	
	public static $handlerList = null;
	
}
