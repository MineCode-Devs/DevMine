<?php



namespace devmine\server\permissions;

use devmine\Server;
use devmine\utilities\main\MainLogger;

class BanList{

	/** @var BanEntry[] */
	private $list = [];

	/** @var string */
	private $file;

	/** @var bool */
	private $enabled = true;

	/**
	 * @param string $file
	 */
	public function __construct($file){
		$this->file = $file;
	}

	/**
	 * @return bool
	 */
	public function isEnabled(){
		return $this->enabled === true;
	}

	/**
	 * @param bool $flag
	 */
	public function setEnabled($flag){
		$this->enabled = (bool) $flag;
	}

	/**
	 * @return BanEntry[]
	 */
	public function getEntries(){
		$this->removeExpired();

		return $this->list;
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function isBanned($name){
		$name = strtolower($name);
		if(!$this->isEnabled()){
			return false;
		}else{
			$this->removeExpired();

			return isset($this->list[$name]);
		}
	}

	/**
	 * @param BanEntry $entry
	 */
	public function add(BanEntry $entry){
		$this->list[$entry->getName()] = $entry;
		$this->save();
	}

	/**
	 * @param string    $target
	 * @param string    $reason
	 * @param \DateTime $expires
	 * @param string    $source
	 *
	 * @return BanEntry
	 */
	public function addBan($target, $reason = null, $expires = null, $source = null){
		$entry = new BanEntry($target);
		$entry->setSource($source != null ? $source : $entry->getSource());
		$entry->setExpires($expires);
		$entry->setReason($reason != null ? $reason : $entry->getReason());

		$this->list[$entry->getName()] = $entry;
		$this->save();

		return $entry;
	}

	/**
	 * @param string $name
	 */
	public function remove($name){
		$name = strtolower($name);
		if(isset($this->list[$name])){
			unset($this->list[$name]);
			$this->save();
		}
	}

	public function removeExpired(){
		foreach($this->list as $name => $entry){
			if($entry->hasExpired()){
				unset($this->list[$name]);
			}
		}
	}

	public function load(){
		$this->list = [];
		$fp = @fopen($this->file, "r");
		if(is_resource($fp)){
			while(($line = fgets($fp)) !== false){
				if($line{0} !== "#"){
					$entry = BanEntry::fromString($line);
					if($entry instanceof BanEntry){
						$this->list[$entry->getName()] = $entry;
					}
				}
			}
			fclose($fp);
		}else{
			MainLogger::getLogger()->error("Could not load ban list");
		}
	}

	public function save($flag = true){
		$this->removeExpired();
		$fp = @fopen($this->file, "w");
		if(is_resource($fp)){
			if($flag === true){
				fwrite($fp, "# Updated " . strftime("%x %H:%M", time()) . " by " . Server::getInstance()->getName() . " " . Server::getInstance()->getdevmineVersion() . "\n");
				fwrite($fp, "# victim name | ban date | banned by | banned until | reason\n\n");
			}

			foreach($this->list as $entry){
				fwrite($fp, $entry->getString() . "\n");
			}
			fclose($fp);
		}else{
			MainLogger::getLogger()->error("Could not save ban list");
		}
	}

}