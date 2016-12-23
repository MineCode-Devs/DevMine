<?php



namespace devmine\server\epilogos;

use devmine\pluginfeatures\Plugin;

abstract class epilogosValue{
	/** @var \WeakRef<Plugin> */
	protected $owningPlugin;

	protected function __construct(Plugin $owningPlugin){
		$this->owningPlugin = new \WeakRef($owningPlugin);
	}

	/**
	 * @return Plugin
	 */
	public function getOwningPlugin(){
		return $this->owningPlugin->get();
	}

	/**
	 * Fetches the value of this epilogos item.
	 *
	 * @return mixed
	 */
	public abstract function value();

	/**
	 * Invalidates this epilogos item, forcing it to recompute when next
	 * accessed.
	 */
	public abstract function invalidate();
}