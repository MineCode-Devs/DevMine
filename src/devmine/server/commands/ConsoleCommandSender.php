<?php



namespace devmine\server\commands;

use devmine\events\TextContainer;
use devmine\server\perms\PermissibleBase;
use devmine\server\perms\PermissionAttachment;
use devmine\consumer\plugin\Plugin;
use devmine\server\server;
use devmine\utilities\main\MainLogger;

class ConsoleCommandSender implements CommandSender{

	private $perm;

	public function __construct(){
		$this->perm = new PermissibleBase($this);
	}

	/**
	 * @param \devmine\server\perms\Permission|string $name
	 *
	 * @return bool
	 */
	public function isPermissionSet($name){
		return $this->perm->isPermissionSet($name);
	}

	/**
	 * @param \devmine\server\perms\Permission|string $name
	 *
	 * @return bool
	 */
	public function hasPermission($name){
		return $this->perm->hasPermission($name);
	}

	/**
	 * @param Plugin $plugin
	 * @param string $name
	 * @param bool   $value
	 *
	 * @return \devmine\server\perms\PermissionAttachment
	 */
	public function addAttachment(Plugin $plugin, $name = null, $value = null){
		return $this->perm->addAttachment($plugin, $name, $value);
	}

	/**
	 * @param PermissionAttachment $attachment
	 *
	 * @return void
	 */
	public function removeAttachment(PermissionAttachment $attachment){
		$this->perm->removeAttachment($attachment);
	}

	public function recalculatePermissions(){
		$this->perm->recalculatePermissions();
	}

	/**
	 * @return \devmine\server\perms\PermissionAttachmentInfo[]
	 */
	public function getEffectivePermissions(){
		return $this->perm->getEffectivePermissions();
	}

	/**
	 * @return bool
	 */
	public function isPlayer(){
		return false;
	}

	/**
	 * @return \devmine\server\server
	 */
	public function getServer(){
		return Server::getInstance();
	}

	/**
	 * @param string $message
	 */
	public function sendMessage($message){
		if($message instanceof TextContainer){
			$message = $this->getServer()->getLanguage()->translate($message);
		}else{
			$message = $this->getServer()->getLanguage()->translateString($message);
		}

		foreach(explode("\n", trim($message)) as $line){
			MainLogger::getLogger()->info($line);
		}
	}

	/**
	 * @return string
	 */
	public function getName() : string{
		return "CONSOLE";
	}

	/**
	 * @return bool
	 */
	public function isOp(){
		return true;
	}

	/**
	 * @param bool $value
	 */
	public function setOp($value){

	}

}