<?php



/**
 * Block related events
 */
namespace devmine\events\block;

use devmine\inventory\blocks\Block;
use devmine\events\Event;

abstract class BlockEvent extends Event{
	/** @var \devmine\inventory\blocks\Block */
	protected $block;

	/**
	 * @param Block $block
	 */
	public function __construct(Block $block){
		$this->block = $block;
	}

	/**
	 * @return Block
	 */
	public function getBlock(){
		return $this->block;
	}
}