<?php



namespace devmine\consumer\resourcepacks;

class ResourcePackInfoEntry{
	protected $packId; //UUID
	protected $version;
	protected $uint64; // unknown

	public function __construct(string $packId, string $version, $uint64){
		$this->packId = $packId;
		$this->version = $version;
		$this->uint64 = $uint64;
	}

	public function getPackId() : string{
		return $this->packId;
	}

	public function getVersion() : string{
		return $this->version;
	}

	public function getUint64(){
		return $this->uint64;
	}

}