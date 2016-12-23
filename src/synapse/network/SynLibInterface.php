<?php


 
namespace synapse\network;

use devmine\server\network\protocol\DataPacket;
use devmine\server\network\SourceInterface;
use devmine\Player;
use synapse\network\protocol\spp\RedirectPacket;
use synapse\Synapse;

class SynLibInterface implements SourceInterface{
	private $synapseInterface;
	private $synapse;

	public function __construct(Synapse $synapse, SynapseInterface $interface){
		$this->synapse = $synapse;
		$this->synapseInterface = $interface;
	}

	public function emergencyShutdown(){
	}

	public function setName($name){
	}

	public function process(){
	}

	public function close(Player $player, $reason = "unknown reason"){
	}

	public function putPacket(Player $player, DataPacket $packet, $needACK = false, $immediate = true){
		$packet->encode();
		$pk = new RedirectPacket();
		$pk->uuid = $player->getUniqueId();
		$pk->direct = $immediate;
		$pk->mcpeBuffer = $packet->buffer;
		$this->synapseInterface->putPacket($pk);
	}

	public function shutdown(){
	}
}