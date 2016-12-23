<?php



namespace devmine\server\events\player;

use devmine\inventory\blocks\Block;
use devmine\server\events\Cancellable;
use devmine\inventory\items\Item;
use devmine\Player;

class PlayerGlassBottleEvent extends PlayerEvent implements Cancellable{
    public static $handlerList = null;

    /** @var Block */
    private $target;
    /** @var Item */
    private $item;

    /**
     * @param Player $Player
     * @param Block  $target
     * @param Item   $itemInHand
     */
    public function __construct(Player $Player, Block $target, Item $itemInHand){
        $this->player = $Player;
        $this->target = $target;
        $this->item = $itemInHand;
    }
    
    /**
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
    public function getBlock(){
        return $this->target;
    }
}