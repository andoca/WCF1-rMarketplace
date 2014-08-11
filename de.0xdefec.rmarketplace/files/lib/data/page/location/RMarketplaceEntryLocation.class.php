<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/page/location/Location.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');

/**
 * RMarketplaceEntryLocation is an implementation of Location for the RMarketplaceEntry page.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceEntryLocation implements Location {
	public $entries = array();

	/**
	 * @see Location::cache()
	 */
	public function cache($location, $requestURI, $requestMethod, $match) {}

	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {

		$entryID = $match[1];

		if (! isset($this->entries[$entryID])) {
			$this->entries[$entryID] = new MpEntry(intval($entryID));
			if ($this->entries[$entryID]->entryID == 0) return '';
		}

		if (! isset($this->entries[$entryID]) || ! $this->entries[$entryID]->userCanView()) {
			return '';
		}

		return WCF::getLanguage()->get($location['locationName'], array(
				'$entry' => '<a href="index.php?page=RMarketplaceEntry&amp;entryID=' . $this->entries[$entryID]->entryID . SID_ARG_2ND . '">' . StringUtil::encodeHTML($this->entries[$entryID]->subject) . '</a>'
		));
	}
}
?>