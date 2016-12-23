<?php



namespace devmine\inventory\blocks;


class NetherReactor extends Solid{

	protected $id = self::NETHER_REACTOR;

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function getName() : string{
		return "Nether Reactor";
	}

	public function canBeActivated() : bool {
		return true;
	}

}