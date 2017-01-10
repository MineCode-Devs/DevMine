<?php



namespace devmine\server\commands;

use devmine\server\perms\Permissible;

interface CommandSender extends Permissible{

	/**
	 * @param string $message
	 */
	public function sendMessage($message);

	/**
	 * @return \devmine\server\server
	 */
	public function getServer();

	/**
	 * @return string
	 */
	public function getName();


}