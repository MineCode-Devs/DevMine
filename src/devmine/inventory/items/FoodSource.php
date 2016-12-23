<?php



namespace devmine\inventory\items;

use devmine\creatures\entities\Effect;
use devmine\creatures\entities\Entity;

interface FoodSource{
	public function getResidue();
	
	public function getFoodRestore() : int;

	public function getSaturationRestore() : float;

	/**
	 * @return Effect[]
	 */
	public function getAdditionalEffects() : array;
	
	
}
