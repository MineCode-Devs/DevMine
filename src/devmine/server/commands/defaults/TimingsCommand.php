<?php



namespace devmine\server\commands\defaults;

use devmine\server\commands\CommandSender;
use devmine\events\TimingsHandler;
use devmine\events\TranslationContainer;


class TimingsCommand extends VanillaCommand{

	public static $timingStart = 0;

	public function __construct($name){
		parent::__construct(
			$name,
			"%DevMine.command.timings.description",
			"%DevMine.command.timings.usage"
		);
		$this->setPermission("DevMine.command.timings");
	}

	public function execute(CommandSender $sender, $currentAlias, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) !== 1){
			$sender->sendMessage(new TranslationContainer("commands.generic.usage", [$this->usageMessage]));

			return true;
		}

		$mode = strtolower($args[0]);

		if($mode === "on"){
			$sender->getServer()->getPluginManager()->setUseTimings(true);
			TimingsHandler::reload();
			$sender->sendMessage(new TranslationContainer("DevMine.command.timings.enable"));

			return true;
		}elseif($mode === "off"){
			$sender->getServer()->getPluginManager()->setUseTimings(false);
			$sender->sendMessage(new TranslationContainer("DevMine.command.timings.disable"));
			return true;
		}

		if(!$sender->getServer()->getPluginManager()->useTimings()){
			$sender->sendMessage(new TranslationContainer("DevMine.command.timings.timingsDisabled"));

			return true;
		}

		$paste = $mode === "paste";

		if($mode === "reset"){
			TimingsHandler::reload();
			$sender->sendMessage(new TranslationContainer("DevMine.command.timings.reset"));
		}elseif($mode === "merged" or $mode === "report" or $paste){

			$sampleTime = microtime(true) - self::$timingStart;
			$index = 0;
			$timingFolder = $sender->getServer()->getDataPath() . "timings/";

			if(!file_exists($timingFolder)){
				mkdir($timingFolder, 0777);
			}
			$timings = $timingFolder . "timings.txt";
			while(file_exists($timings)){
				$timings = $timingFolder . "timings" . (++$index) . ".txt";
			}

			$fileTimings = $paste ? fopen("php://temp", "r+b") : fopen($timings, "a+b");

			TimingsHandler::printTimings($fileTimings);

			fwrite($fileTimings, "Sample time " . round($sampleTime * 1000000000) . " (" . $sampleTime . "s)" . PHP_EOL);

			if($paste){
				fseek($fileTimings, 0);
				$data = [
					"syntax" => "text",
					"poster" => $sender->getServer()->getName(),
					"content" => stream_get_contents($fileTimings)
				];

				$ch = curl_init("http://paste.ubuntu.com/");
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
				curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_AUTOREFERER, false);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
				curl_setopt($ch, CURLOPT_HEADER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, ["User-Agent: " . $this->getName() . " " . $sender->getServer()->getDevMineVersion()]);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				$data = curl_exec($ch);
				curl_close($ch);
				if(preg_match('#^Location: http://paste\\.ubuntu\\.com/([0-9]{1,})/#m', $data, $matches) == 0){
					$sender->sendMessage(new TranslationContainer("DevMine.command.timings.pasteError"));

					return true;
				}


				$sender->sendMessage(new TranslationContainer("DevMine.command.timings.timingsUpload", ["http://paste.ubuntu.com/" . $matches[1] . "/"]));
				$sender->sendMessage(new TranslationContainer("DevMine.command.timings.timingsRead", ["http://timings.aikar.co/?url=" . $matches[1]]));
				fclose($fileTimings);
			}else{
				fclose($fileTimings);
				$sender->sendMessage(new TranslationContainer("DevMine.command.timings.timingsWrite", [$timings]));
			}
		}

		return true;
	}
}
