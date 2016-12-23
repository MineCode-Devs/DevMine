<?php



namespace devmine\server\epilogos;

use devmine\pluginfeatures\Plugin;

interface epilogosble{

	/**
	 * Sets a epilogos value in the implementing object's epilogos store.
	 *
	 * @param string        $epilogosKey
	 * @param epilogosValue $newepilogosValue
	 *
	 * @return void
	 */
	public function setepilogos($epilogosKey, epilogosValue $newepilogosValue);

	/**
	 * Returns a list of previously set epilogos values from the implementing
	 * object's epilogos store.
	 *
	 * @param string $epilogosKey
	 *
	 * @return epilogosValue[]
	 */
	public function getepilogos($epilogosKey);

	/**
	 * Tests to see whether the implementing object contains the given
	 * epilogos value in its epilogos store.
	 *
	 * @param string $epilogosKey
	 *
	 * @return boolean
	 */
	public function hasepilogos($epilogosKey);

	/**
	 * Removes the given epilogos value from the implementing object's
	 * epilogos store.
	 *
	 * @param string $epilogosKey
	 * @param Plugin $owningPlugin
	 *
	 * @return void
	 */
	public function removeepilogos($epilogosKey, Plugin $owningPlugin);

}