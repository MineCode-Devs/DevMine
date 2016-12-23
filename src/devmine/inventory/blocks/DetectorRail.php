<?php



namespace devmine\inventory\blocks;

class DetectorRail extends PoweredRail{

    protected $id = self::DETECTOR_RAIL;

    public function __construct($meta = 0){
        $this->meta = $meta;
    }

    public function getName() : string {
        return "Detector Rail";
    }
}
