<?php



namespace devmine\server\perms;


interface ServerOperator{
	/**
	 * Checks if the current object has operator permissions
	 *
	 * @return bool
	 */
	public function isOp();

	/**
	 * Sets the operator permission for the current object
	 *
	 * @param bool $value
	 *
	 * @return void
	 */
	public function setOp($value);
}