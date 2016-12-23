<?php



namespace devmine\creatures\entities;


interface Ageable{
	const DATA_AGEABLE_FLAGS = 14;

	const DATA_FLAG_BABY = 0;

	public function isBaby();
}