<?php



namespace devmine\server\tasks;

/**
 * Allows the creation of simple callbacks with extra data
 * The last parameter in the callback will be this object
 *
 * If you want to do a task in a Plugin, consider extending PluginTask to your needs
 *
 * @deprecated 
 * Do NOT use this anymore, it was deprecated a long time ago at DevMine
 * and will be removed at some stage in the future.
 */

class CallbackTask extends Task{

	/** @var callable */
	protected $callable;

	/** @var array */
	protected $args;

	/**
	 * @param callable $callable
	 * @param array    $args
	 */
	public function __construct(callable $callable, array $args = []){
		$this->callable = $callable;
		$this->args = $args;
		$this->args[] = $this;
	}

	/**
	 * @return callable
	 */
	public function getCallable(){
		return $this->callable;
	}

	public function onRun($currentTicks){
		call_user_func_array($this->callable, $this->args);
	}

}
