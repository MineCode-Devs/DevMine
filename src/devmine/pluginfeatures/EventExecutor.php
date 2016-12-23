<?php



namespace devmine\pluginfeatures;

use devmine\server\events\Event;
use devmine\server\events\Listener;

interface EventExecutor{

	/**
	 * @param Listener $listener
	 * @param Event    $event
	 *
	 * @return void
	 */
	public function execute(Listener $listener, Event $event);
}