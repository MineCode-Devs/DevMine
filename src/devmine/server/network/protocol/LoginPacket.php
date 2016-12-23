<?php



namespace devmine\server\network\protocol;

#include <rules/DataPacket.h>


class LoginPacket extends DataPacket{
	const NETWORK_ID = Info::LOGIN_PACKET;

	public $username;
	public $protocol;

	public $clientUUID;
	public $clientId;
	public $identityPublickey;
	public $serverAddress;

	public $skinId = null;
	public $skin = null;

	public function decode(){
		//$this->username = $this->getString();
		//$this->protocol1 = $this->getInt();
		//$this->protocol2 = $this->getInt();
		/*if($this->protocol1 < Info::CURRENT_PROTOCOL){ //New fields!
			$this->setBuffer(null, 0); //Skip batch packet handling
			return;
		}*/
		$this->protocol = $this->getInt();
		
		$str = zlib_decode($this->get($this->getInt()),1024 *1024 * 64);
		$this->setBuffer($str,0);
		
		$chainData = json_decode($this->get($this->getLInt()));
		foreach($chainData->{"chain"} as $chain){
			$webtoken = $this->decodeToken($chain);
			if(isset($webtoken["extraData"])){
				if(isset($webtoken["extraData"]["displayName"])){
					$this->username = $webtoken["extraData"]["displayName"];
				}
				if(isset($webtoken["extraData"]["identity"])){
					$this->clientUUID = $webtoken["extraData"]["identity"];
				}
				if(isset($webtoken["identityPublicKey"])){
					$this->identityPublicKey = $webtoken["identityPublicKey"];
				}
			}
		}
		$skinToken = $this->decodeToken($this->get($this->getLInt()));
		if(isset($skinToken["ClientRandomId"])){
			$this->clientId = $skinToken["ClientRandomId"];
		}
		if(isset($skinToken["ServerAddress"])){
			$this->serverAddress = $skinToken["ServerAddress"];
		}
		if(isset($skinToken["SkinData"])){
			$this->skin = base64_decode($skinToken["SkinData"]);
		}
		if(isset($skinToken["SkinId"])){
			$this->skinId = $skinToken["SkinId"];
		}
	}

	public function encode(){

	}
	
	public function decodeToken($token){
		$tokens = explode(".", $token);
		list($headB64, $payloadB64, $sigB64) = $tokens;
	
		return json_decode(base64_decode($payloadB64), true);
	}

}