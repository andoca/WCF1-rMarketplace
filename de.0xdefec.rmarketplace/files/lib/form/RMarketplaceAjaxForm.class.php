<?php
// wcf imports
require_once (WCF_DIR . 'lib/form/AbstractSecureForm.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');

/**
 * ajax form used in the rmarketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceAjaxForm extends AbstractSecureForm {
	// methode to be called
	public $methode;

	// lat/lng data of "getMarkers" methode
	public $boundsSWLat;
	public $boundsSWLng;
	public $boundsNELat;
	public $boundsNELng;

	public $mapType = null;

	/**
	 * selected category
	 *
	 * @var integer
	 */
	public $cat = 0;

	/**
	 * object of the selected category
	 *
	 * @var string
	 */
	public $category = null;

	public $type = null;

	/**
	 * @see Page::readParameters();
	 */
	public function readFormParameters() {
		parent::readFormParameters();

	}

	/**
	 * @see Page::readParameters();
	 */
	public function readParameters() {
		parent::readParameters();

		// check security token
		$this->checkSecurityToken();

		if (isset($_REQUEST['methode'])) $this->methode = StringUtil::trim($_REQUEST['methode']);
		if (isset($_REQUEST['cat'])) $this->cat = intval($_REQUEST['cat']);
		if (isset($_REQUEST['type'])) $this->type = StringUtil::trim($_REQUEST['type']);

		if (isset($_REQUEST['boundsSWLat'])) $this->boundsSWLat = floatval($_REQUEST['boundsSWLat']);
		if (isset($_REQUEST['boundsSWLng'])) $this->boundsSWLng = floatval($_REQUEST['boundsSWLng']);
		if (isset($_REQUEST['boundsNELat'])) $this->boundsNELat = floatval($_REQUEST['boundsNELat']);
		if (isset($_REQUEST['boundsNELng'])) $this->boundsNELng = floatval($_REQUEST['boundsNELng']);

		if (isset($_REQUEST['mapType'])) $this->mapType = intval($_REQUEST['mapType']);

	}

	/**
	 * @see Page::assignData();
	 */
	public function readData() {
		parent::readData();

		if ($this->methode == 'getMarkers') {
			$this->readMarkers();
		}

		// avoid session update
		WCF::getSession()->disableUpdate();

		WCF::getUser()->checkPermission('user.rmarketplace.canList');
		// if (! isset($this->address) || ! isset($this->radius)) throw new IllegalLinkException();
	}

	private function readMarkers($hideOld = null) {
		if ($hideOld == null) $hideOld = MP_GMAP_HIDE_OLD;

		$list = new RMarketplaceList();
		$list->category = $this->cat;
		$list->type = $this->type;

		$entries = $list->getBounds($this->boundsSWLng, $this->boundsNELng, $this->boundsNELat, $this->boundsSWLat);

		$outputArray = array();

		$i = 0;

		if ($this->mapType == 'largeMap')
			$hideOld = MP_GMAP_LARGE_HIDE_OLD;
		else
			$hideOld = MP_GMAP_HIDE_OLD;

		foreach ( $entries as $entry ) {
			if ($entry->isOld() && $hideOld) continue;
			$outputArray[$i]['id'] = $entry->entryID;
			$outputArray[$i]['type'] = $entry->type;
			$outputArray[$i]['lat'] = $entry->lat;
			$outputArray[$i]['lng'] = $entry->lng;

			WCF::getTPL()->assign(array(
					'entry' => $entry
			));
			$outputArray[$i]['infoWindow'] = WCF::getTPL()->fetch('rmarketplaceMarkerInfoWindow');

			/*if(defined('CHARSET')) {
				if(CHARSET != 'UTF-8') $outputArray[$i]['infoWindow'] = StringUtil::convertEncoding(CHARSET, 'UTF-8', $outputArray[$i]['infoWindow']);
			}*/

			$i++;
		}

		$output = $this->array2json($outputArray);

		header('Content-type: application/json; charset=' . CHARSET);
		echo $output;
	}

	private function array2json($arr) {
		// if(function_exists('json_encode') && 1 == 2) return json_encode($arr); //Lastest versions of PHP already has this functionality. but does not handle non utf8 strings well
		if (! $arr) return "[]";
		$parts = array();
		$is_list = false;

		//Find out if the given array is a numerical array
		$keys = array_keys($arr);
		$max_length = count($arr) - 1;
		if (($keys[0] == 0) and ($keys[$max_length] == $max_length)) { //See if the first key is 0 and last key is length - 1
			$is_list = true;
			for($i = 0; $i < count($keys); $i++) { //See if each key correspondes to its position
				if ($i != $keys[$i]) { //A key fails at position check.
					$is_list = false; //It is an associative array.
					break;
				}
			}
		}

		foreach ( $arr as $key => $value ) {
			if (is_array($value)) { //Custom handling for arrays
				if ($is_list)
					$parts[] = $this->array2json($value); /* :RECURSION: */
				else
					$parts[] = '"' . $key . '":' . $this->array2json($value); /* :RECURSION: */
			}
			else {
				$str = '';
				if (! $is_list) $str = '"' . $key . '":';

				//Custom handling for multiple data types
				if (is_numeric($value))
					$str .= $value; //Numbers
				elseif ($value === false)
					$str .= 'false'; //The booleans
				elseif ($value === true)
					$str .= 'true';
				else
					$str .= '"' . addslashes(preg_replace("/[\n\r]/", "", $value)) . '"'; //All other things -- remove newlines
				// :TODO: Is there any more datatype we should be in the lookout for? (Object?)


				$parts[] = $str;
			}
		}
		$json = implode(',', $parts);

		if ($is_list) return '[' . $json . ']'; //Return numerical JSON
		return '{' . $json . '}'; //Return associative JSON
	}

	private function encodeJS($str) {
		$str = StringUtil::replace("\\", "\\\\", $str);
		$str = StringUtil::replace("'", "\'", $str);
		$str = StringUtil::replace("\n", '\n', $str);
		$str = StringUtil::replace("/", '\/', $str);
		return $str;
	}
	
	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!MODULE_RMARKETPLACE) {
			throw new IllegalLinkException();
		}
		parent::show();
	}
}
?>