<?php



namespace devmine\server\calculations;


abstract class VectorMath{

	public static function getDirection2D($azimuth){
		return new Vector2(cos($azimuth), sin($azimuth));
	}

}