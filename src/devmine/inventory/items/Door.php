<?php


 
 namespace devmine\inventory\items;
 
 abstract class Door extends Item{
	public function getMaxStackSize() : int {
		return 64;
	}
}