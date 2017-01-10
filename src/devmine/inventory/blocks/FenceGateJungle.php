<?php



namespace devmine\inventory\blocks;


class FenceGateJungle extends FenceGate{

	protected $id = self::FENCE_GATE_JUNGLE;

	public function getName() : string{
		return "Jungle Fence Gate";
	}
}