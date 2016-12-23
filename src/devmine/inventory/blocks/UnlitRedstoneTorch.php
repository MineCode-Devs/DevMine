<?php



namespace devmine\inventory\blocks;

class UnlitRedstoneTorch extends RedstoneTorch{
	protected $id = self::UNLIT_REDSTONE_TORCH;

	public function getLightLevel(){
		return 0;
	}

	public function isActivated(Block $from = null){
		return false;
	}
}