<?php



namespace devmine\events\player;

use devmine\inventory\blocks\Block;
use devmine\inventory\items\Item;
use devmine\creatures\player;

class PlayerBucketEmptyEvent extends PlayerBucketEvent{
	public static $handlerList = null;

	public function __construct(Player $who, Block $blockClicked, $blockFace, Item $bucket, Item $itemInHand){
		parent::__construct($who, $blockClicked, $blockFace, $bucket, $itemInHand);
	}
}