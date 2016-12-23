<?php



namespace devmine\server\commands;

use devmine\server\permissions\Permissible;

interface CommandSender extends Permissible{

	/**
	 * @param string $message
	 */
	public function sendMessage($message);

	/**
	 * @return \devmine\Server
	 */
	public function getServer();

	/**
	 * @return string
	 */
	public function getName();


}