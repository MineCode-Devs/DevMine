<?php



namespace devmine\events\inventory;

use devmine\events\block\BlockEvent;
use devmine\events\Cancellable;
use devmine\inventory\items\Item;
use devmine\inventory\solidentity\Furnace;

class FurnaceSmeltEvent extends BlockEvent implements Cancellable{
	public static $handlerList = null;

	private $furnace;
	private $source;
	private $result;

	public function __construct(Furnace $furnace, Item $source, Item $result){
		parent::__construct($furnace->getBlock());
		$this->source = clone $source;
		$this->source->setCount(1);
		$this->result = $result;
		$this->furnace = $furnace;
	}

	/**
	 * @return Furnace
	 */
	public function getFurnace(){
		return $this->furnace;
	}

	/**
	 * @return Item
	 */
	public function getSource(){
		return $this->source;
	}

	/**
	 * @return Item
	 */
	public function getResult(){
		return $this->result;
	}

	/**
	 * @param Item $result
	 */
	public function setResult(Item $result){
		$this->result = $result;
	}
}