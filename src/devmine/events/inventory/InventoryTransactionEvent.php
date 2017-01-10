<?php



namespace devmine\events\inventory;

use devmine\events\Cancellable;
use devmine\events\Event;
use devmine\inventory\layout\TransactionQueue;

/**
 * Called when an inventory transaction queue starts execution. 
 */

class InventoryTransactionEvent extends Event implements Cancellable{

	public static $handlerList = null;
	
	/** @var TransactionQueue */
	private $transactionQueue;
	
	/**
	 * @param TransactionQueue $ts
	 */
	public function __construct(TransactionQueue $transactionQueue){
		$this->transactionQueue = $transactionQueue;
	}

	/**
	 * @deprecated
	 * @return TransactionQueue
	 */
	public function getTransaction(){
		return $this->transactionQueue;
	}

	/**
	 * @return TransactionQueue
	 */
	public function getQueue(){
		return $this->transactionQueue;
	}
}