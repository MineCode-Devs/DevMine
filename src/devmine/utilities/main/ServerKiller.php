<?php



namespace devmine\utilities\main;

use devmine\server\Thread;

class ServerKiller extends Thread{

	public $time;

	public function __construct($time = 15){
		$this->time = $time;
	}

	public function run(){
		$start = time();
		$this->synchronized(function(){
			$this->wait($this->time * 1000000);
		});
		if(time() - $start >= $this->time){
			echo "\nTook too long to stop, server was killed forcefully!\n";
			@\DevMine\kill(getmypid());
		}
	}

	public function getThreadName(){
		return "Server Killer";
	}
}
