<?php



namespace devmine\events\block;

use devmine\inventory\blocks\Block;
use devmine\events\Cancellable;
use devmine\creatures\player;

/**
 * Called when a sign is changed by a player.
 */
class SignChangeEvent extends BlockEvent implements Cancellable{
	public static $handlerList = null;

	/** @var \devmine\creatures\player */
	private $player;
	/** @var string[] */
	private $lines = [];

	/**
	 * @param Block    $theBlock
	 * @param Player   $thePlayer
	 * @param string[] $theLines
	 */
	public function __construct(Block $theBlock, Player $thePlayer, array $theLines){
		parent::__construct($theBlock);
		$this->player = $thePlayer;
		$this->lines = $theLines;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(){
		return $this->player;
	}

	/**
	 * @return string[]
	 */
	public function getLines(){
		return $this->lines;
	}

	/**
	 * @param int $index 0-3
	 *
	 * @return string
	 */
	public function getLine($index){
		return $this->lines[$index];
	}

	/**
	 * @param int    $index 0-3
	 * @param string $line
	 */
	public function setLine($index, $line){
		$this->lines[$index] = $line;
	}
}