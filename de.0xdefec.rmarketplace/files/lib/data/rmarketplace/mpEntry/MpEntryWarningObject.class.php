<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/infraction/warning/object/WarningObject.class.php');
require_once(WCF_DIR.'lib/data/rmarketplace/mpEntry/MpEntry.class.php');

/**
 * An implementation of WarningObject to support the usage of a mpEntry as a warning object.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class MpEntryWarningObject extends MpEntry implements WarningObject {
	/**
	 * @see WarningObject::getTitle()
	 */
	public function getTitle() {
		return $this->data['subject'];
	}

	/**
	 * @see WarningObject::getURL()
	 */
	public function getURL() {
		return 'index.php?page=RMarketplaceEntry&entryID='.$this->entryID;
	}
}
?>