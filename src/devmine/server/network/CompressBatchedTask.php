<?php



namespace devmine\server\network;

use devmine\creatures\player;
use devmine\server\tasks\AsyncTask;
use devmine\server\server;

class CompressBatchedTask extends AsyncTask{

	public $level = 7;
	public $data;
	public $final;
	public $targets;

	public function __construct($data, array $targets, $level = 7){
		$this->data = $data;
		$this->targets = serialize($targets);
		$this->level = $level;
	}

	public function onRun(){
		try{
			$this->final = zlib_encode($this->data, ZLIB_ENCODING_DEFLATE, $this->level);
			$this->data = null;
		}catch(\Throwable $e){

		}
	}

	public function onCompletion(Server $server){
		$server->broadcastPacketsCallback($this->final, unserialize($this->targets));
	}
}