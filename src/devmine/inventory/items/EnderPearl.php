<?php



namespace devmine\inventory\items;
use devmine\inventory\items\Item;

class EnderPearl extends Item{

    public function __construct($meta = 0, $count = 1){
        parent::__construct(self::ENDER_PEARL, 0, $count, "Ender Pearl");
    }

    public function getMaxStackSize() : int {
        return 16;
    }

}
