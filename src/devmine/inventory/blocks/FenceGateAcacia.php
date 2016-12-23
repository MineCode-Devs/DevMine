<?php



namespace devmine\inventory\blocks;


class FenceGateAcacia extends FenceGate{

	protected $id = self::FENCE_GATE_ACACIA;

	public function getName() : string{
		return "Acacia Fence Gate";
	}
}