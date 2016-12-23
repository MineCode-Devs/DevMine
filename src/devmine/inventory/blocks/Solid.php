<?php



namespace devmine\inventory\blocks;

abstract class Solid extends Block{

	public function isSolid(){
		return true;
	}
}