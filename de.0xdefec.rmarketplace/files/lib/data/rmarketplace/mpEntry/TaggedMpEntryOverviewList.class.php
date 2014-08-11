<?php
// wcf imports
require_once(WCF_DIR.'lib/data/rmarketplace/mpEntry/TaggedMpEntryList.class.php');

/**
 * Represents a list of tagged rMarketplace entries.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class TaggedMpEntryOverviewList extends TaggedMpEntryList {
	/**
	 * Creates a new TaggedMpEntryOverviewList object.
	 */
	public function __construct($tagID) {
		$this->sqlSelects = 'user_table.username';
		$this->sqlJoins = "LEFT JOIN wcf".WCF_N."_user user_table ON (user_table.userID = rmarketplace.userID)";
		parent::__construct($tagID);
	}
}
?>