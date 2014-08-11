<?php

/**
 * reprensents a list of countries
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 * @todo if needed outsource to other plugin to let the user set all available countries
 */
class Countries {
	public $countries = array();

	public function __construct() {
		$lines = ArrayUtil::trim(preg_split("/[\n\r]+/", MP_COUNTRIES));
		foreach ( $lines as $line ) {
			$data = explode(";", $line, 2);
			if (substr($data[0], 0, 1) != '#') $this->countries[$data[0]] = $data[1];
		}

		$this->countries = ArrayUtil::trim($this->countries);
	}

	/**
	 * gets the list of countries
	 *
	 * @return array
	 */
	public function get() {
		return $this->countries;
	}
}

?>