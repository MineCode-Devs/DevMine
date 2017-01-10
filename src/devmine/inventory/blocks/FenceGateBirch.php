<?php



namespace devmine\inventory\blocks;


class FenceGateBirch extends FenceGate{

	protected $id = self::FENCE_GATE_BIRCH;

	public function getName() : string{
		return "Birch Fence Gate";
	}
}