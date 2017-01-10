<?php



namespace devmine\events;


/**
 * Events that can be cancelled must use the interface Cancellable
 */
interface Cancellable{
	public function isCancelled();

	public function setCancelled($forceCancel = false);
}