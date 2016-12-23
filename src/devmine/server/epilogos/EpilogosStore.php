<?php



/**
 * Saves extra data on runtime for different items
 */
namespace devmine\server\epilogos;

use devmine\pluginfeatures\Plugin;
use devmine\utilities\main\PluginException;

abstract class epilogosStore{
	/** @var \WeakMap[] */
	private $epilogosMap;

	/**
	 * Adds a epilogos value to an object.
	 *
	 * @param mixed         $subject
	 * @param string        $epilogosKey
	 * @param epilogosValue $newepilogosValue
	 *
	 * @throws \Exception
	 */
	public function setepilogos($subject, $epilogosKey, epilogosValue $newepilogosValue){
		$owningPlugin = $newepilogosValue->getOwningPlugin();
		if($owningPlugin === null){
			throw new PluginException("Plugin cannot be null");
		}

		$key = $this->disambiguate($subject, $epilogosKey);
		if(!isset($this->epilogosMap[$key])){
			//$entry = new \WeakMap();
			$this->epilogosMap[$key] = new \SplObjectStorage();//$entry;
		}else{
			$entry = $this->epilogosMap[$key];
		}
		$entry[$owningPlugin] = $newepilogosValue;
	}

	/**
	 * Returns all epilogos values attached to an object. If multiple
	 * have attached epilogos, each will value will be included.
	 *
	 * @param mixed  $subject
	 * @param string $epilogosKey
	 *
	 * @return epilogosValue[]
	 *
	 * @throws \Exception
	 */
	public function getepilogos($subject, $epilogosKey){
		$key = $this->disambiguate($subject, $epilogosKey);
		if(isset($this->epilogosMap[$key])){
			return $this->epilogosMap[$key];
		}else{
			return [];
		}
	}

	/**
	 * Tests to see if a epilogos attribute has been set on an object.
	 *
	 * @param mixed  $subject
	 * @param string $epilogosKey
	 *
	 * @return bool
	 *
	 * @throws \Exception
	 */
	public function hasepilogos($subject, $epilogosKey){
		return isset($this->epilogosMap[$this->disambiguate($subject, $epilogosKey)]);
	}

	/**
	 * Removes a epilogos item owned by a plugin from a subject.
	 *
	 * @param mixed  $subject
	 * @param string $epilogosKey
	 * @param Plugin $owningPlugin
	 *
	 * @throws \Exception
	 */
	public function removeepilogos($subject, $epilogosKey, Plugin $owningPlugin){
		$key = $this->disambiguate($subject, $epilogosKey);
		if(isset($this->epilogosMap[$key])){
			unset($this->epilogosMap[$key][$owningPlugin]);
			if($this->epilogosMap[$key]->count() === 0){
				unset($this->epilogosMap[$key]);
			}
		}
	}

	/**
	 * Invalidates all epilogos in the epilogos store that originates from the
	 * given plugin. Doing this will force each invalidated epilogos item to
	 * be recalculated the next time it is accessed.
	 *
	 * @param Plugin $owningPlugin
	 */
	public function invalidateAll(Plugin $owningPlugin){
		/** @var $values epilogosValue[] */
		foreach($this->epilogosMap as $values){
			if(isset($values[$owningPlugin])){
				$values[$owningPlugin]->invalidate();
			}
		}
	}

	/**
	 * Creates a unique name for the object receiving epilogos by combining
	 * unique data from the subject with a epilogosKey.
	 *
	 * @param epilogosble $subject
	 * @param string      $epilogosKey
	 *
	 * @return string
	 *
	 * @throws \InvalidArgumentException
	 */
	public abstract function disambiguate(epilogosble $subject, $epilogosKey);
}