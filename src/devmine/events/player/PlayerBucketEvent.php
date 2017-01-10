<?php



namespace devmine\events\player;

use devmine\inventory\blocks\Block;
use devmine\events\Cancellable;
use devmine\inventory\items\Item;
use devmine\creatures\player;

abstract class PlayerBucketEvent extends PlayerEvent implements Cancellable{

	/** @var Block */
	private $blockClicked;
	/** @var int */
	private $blockFace;
	/** @var Item */
	private $bucket;
	/** @var Item */
	private $item;

	/**
	 * @param Player $who
	 * @param Block  $blockClicked
	 * @param int    $blockFace
	 * @param Item   $bucket
	 * @param Item   $itemInHand
	 */
	public function __construct(Player $who, Block $blockClicked, $blockFace, Item $bucket, Item $itemInHand){
		$this->player = $who;
		$this->blockClicked = $blockClicked;
		$this->blockFace = (int) $blockFace;
		$this->item = $itemInHand;
		$this->bucket = $bucket;
	}

	/**
	 * Returns the bucket used in this event
	 *
	 * @return Item
	 */
	public function getBucket(){
		return $this->bucket;
	}

	/**
	 * Returns the item in hand after the event
	 *
	 * @return Item
	 */
	public function getItem(){
		return $this->item;
	}

	/**
	 * @param Item $item
	 */
	public function setItem(Item $item){
		$this->item = $item;
	}

	/**
	 * @return Block
	 */
	public function getBlockClicked(){
		return $this->blockClicked;
	}

	/**
	 * @return int
	 */
	public function getBlockFace(){
		return $this->blockFace;
	}
}