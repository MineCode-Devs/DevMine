<?php



namespace devmine\inventory\blocks;

class ActivatorRail extends PoweredRail {

    protected $id = self::ACTIVATOR_RAIL;

    public function __construct($meta = 0){
        $this->meta = $meta;
    }

    public function getName() : string {
        return "Activator Rail";
    }
}
