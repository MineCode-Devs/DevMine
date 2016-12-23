<?php



namespace devmine\creatures\player;


use devmine\server\epilogos\epilogosValue;
use devmine\creatures\player\tag\CompoundTag;
use devmine\pluginfeatures\Plugin;

class OfflinePlayer implements IPlayer{

	private $name;
	private $server;
	private $namedtag;

	/**
	 * @param Server $server
	 * @param string $name
	 */
	public function __construct(Server $server, $name){
		$this->server = $server;
		$this->name = $name;
		if(file_exists($this->server->getDataPath() . "players/" . strtolower($this->getName()) . ".dat")){
			$this->namedtag = $this->server->getOfflinePlayerData($this->name);
		}else{
			$this->namedtag = null;
		}
	}

	public function isOnline(){
		return $this->getPlayer() !== null;
	}

	public function getName() : string{
		return $this->name;
	}

	public function getServer(){
		return $this->server;
	}

	public function isOp(){
		return $this->server->isOp(strtolower($this->getName()));
	}

	public function setOp($value){
		if($value === $this->isOp()){
			return;
		}

		if($value === true){
			$this->server->addOp(strtolower($this->getName()));
		}else{
			$this->server->removeOp(strtolower($this->getName()));
		}
	}

	public function isBanned(){
		return $this->server->getNameBans()->isBanned(strtolower($this->getName()));
	}

	public function setBanned($value){
		if($value === true){
			$this->server->getNameBans()->addBan($this->getName(), null, null, null);
		}else{
			$this->server->getNameBans()->remove($this->getName());
		}
	}

	public function isWhitelisted(){
		return $this->server->isWhitelisted(strtolower($this->getName()));
	}

	public function setWhitelisted($value){
		if($value === true){
			$this->server->addWhitelist(strtolower($this->getName()));
		}else{
			$this->server->removeWhitelist(strtolower($this->getName()));
		}
	}

	public function getPlayer(){
		return $this->server->getPlayerExact($this->getName());
	}

	public function getFirstPlayed(){
		return $this->namedtag instanceof CompoundTag ? $this->namedtag["firstPlayed"] : null;
	}

	public function getLastPlayed(){
		return $this->namedtag instanceof CompoundTag ? $this->namedtag["lastPlayed"] : null;
	}

	public function hasPlayedBefore(){
		return $this->namedtag instanceof CompoundTag;
	}

	public function setepilogos($epilogosKey, epilogosValue $epilogosValue){
		$this->server->getPlayerepilogos()->setepilogos($this, $epilogosKey, $epilogosValue);
	}

	public function getepilogos($epilogosKey){
		return $this->server->getPlayerepilogos()->getepilogos($this, $epilogosKey);
	}

	public function hasepilogos($epilogosKey){
		return $this->server->getPlayerepilogos()->hasepilogos($this, $epilogosKey);
	}

	public function removeepilogos($epilogosKey, Plugin $plugin){
		$this->server->getPlayerepilogos()->removeepilogos($this, $epilogosKey, $plugin);
	}


}
