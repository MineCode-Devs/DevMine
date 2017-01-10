<?php



namespace devmine\server\commands;

use devmine\events\TranslationContainer;
use devmine\server\server;
use devmine\utilities\main\MainLogger;
use devmine\utilities\main\TextFormat;

class FormattedCommandAlias extends Command{
	private $formatStrings = [];

	/**
	 * @param string   $alias
	 * @param string[] $formatStrings
	 */
	public function __construct($alias, array $formatStrings){
		parent::__construct($alias);
		$this->formatStrings = $formatStrings;
	}

	public function execute(CommandSender $sender, $commandLabel, array $args){

		$commands = [];
		$result = false;

		foreach($this->formatStrings as $formatString){
			try{
				$commands[] = $this->buildCommand($formatString, $args);
			}catch(\Throwable $e){
				if($e instanceof \InvalidArgumentException){
					$sender->sendMessage(TextFormat::RED . $e->getMessage());
				}else{
					$sender->sendMessage(new TranslationContainer(TextFormat::RED . "%commands.generic.exception"));
					$logger = $sender->getServer()->getLogger();
					if($logger instanceof MainLogger){
						$logger->logException($e);
					}
				}

				return false;
			}
		}

		foreach($commands as $command){
			$result |= Server::getInstance()->dispatchCommand($sender, $command);
		}

		return (bool) $result;
	}

	/**
	 * @param string $formatString
	 * @param array  $args
	 *
	 * @return string
	 * @throws \InvalidArgumentException
	 */
	private function buildCommand($formatString, array $args){
		$index = strpos($formatString, '$');
		while($index !== false){
			$start = $index;
			if($index > 0 and $formatString{$start - 1} === "\\"){
				$formatString = substr($formatString, 0, $start - 1) . substr($formatString, $start);
				$index = strpos($formatString, '$', $index);
				continue;
			}

			$required = false;
			if($formatString{$index + 1} == '$'){
				$required = true;

				++$index;
			}

			++$index;

			$argStart = $index;

			while($index < strlen($formatString) and self::inRange(ord($formatString{$index}) - 48, 0, 9)){
				++$index;
			}

			if($argStart === $index){
				throw new \InvalidArgumentException("Invalid replacement token");
			}

			$position = intval(substr($formatString, $argStart, $index));

			if($position === 0){
				throw new \InvalidArgumentException("Invalid replacement token");
			}

			--$position;

			$rest = false;

			if($index < strlen($formatString) and $formatString{$index} === "-"){
				$rest = true;
				++$index;
			}

			$end = $index;

			if($required and $position >= count($args)){
				throw new \InvalidArgumentException("Missing required argument " . ($position + 1));
			}

			$replacement = "";
			if($rest and $position < count($args)){
				for($i = $position; $i < count($args); ++$i){
					if($i !== $position){
						$replacement .= " ";
					}

					$replacement .= $args[$i];
				}
			}elseif($position < count($args)){
				$replacement .= $args[$position];
			}

			$formatString = substr($formatString, 0, $start) . $replacement . substr($formatString, $end);

			$index = $start + strlen($replacement);

			$index = strpos($formatString, '$', $index);
		}

		return $formatString;
	}

	/**
	 * @param int $i
	 * @param int $j
	 * @param int $k
	 *
	 * @return bool
	 */
	private static function inRange($i, $j, $k){
		return $i >= $j and $i <= $k;
	}

}