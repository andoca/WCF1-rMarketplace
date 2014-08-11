<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/message/util/SearchResultTextParser.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');

/**
 * This class extends the MpEntry by function for a search result output.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class MpEntrySearchResult extends MpEntry {
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		$this->data['messagePreview'] = true;
	}

	/**
	 * @see MpEntry::getFormattedMessage()
	 */
	public function getFormattedMessage() {
		return SearchResultTextParser::parse(parent::getFormattedMessage());
	}
}
?>