<?php



namespace devmine\inventory\blocks;


class FenceGateDarkOak extends FenceGate{

	protected $id = self::FENCE_GATE_DARK_OAK;

	public function getName() : string{
		return "Dark Oak Fence Gate";
	}
}