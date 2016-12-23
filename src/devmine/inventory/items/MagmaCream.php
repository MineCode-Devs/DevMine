<?php



namespace devmine\inventory\items;

class MagmaCream extends Item {
    public function __construct($meta = 0, $count =1){
        parent::__construct(self::MAGMA_CREAM, $meta, $count, "Magma Cream");
    }
}
