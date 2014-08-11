<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/message/search/AbstractSearchableMessageType.class.php');

/**
 * An implementation of SearchableMessageType for searching in rMarketplace entries.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class MpEntrySearch extends AbstractSearchableMessageType {

	/**
	 * Caches the data of the messages with the given ids.
	 */
	public function cacheMessageData($messageIDs, $additionalData = null) {
		$sql = "SELECT	*
			FROM		wcf" . WCF_N . "_rmarketplace_entries entry
			WHERE		entry.entryID IN (" . $messageIDs . ")";
		$result = WCF::getDB()->sendQuery($sql);
		require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntrySearchResult.class.php');
		while ( $row = WCF::getDB()->fetchArray($result) ) {
			$this->messageCache[$row['entryID']] = array(
					'type' => 'mpentry',
					'message' => new MpEntrySearchResult(null, $row)
			);
		}
	}

	/**
	 * @see SearchableMessageType::getMessageData()
	 */
	public function getMessageData($messageID, $additionalData = null) {
		if (isset($this->messageCache[$messageID])) return $this->messageCache[$messageID];
		return null;
	}

	/**
	 * Shows mpEntry specific form elements in the global search form.
	 */
	public function show($form = null) {	// parent::show();
	}

	/**
	 * Returns the conditions for a search in the table of this search type.
	 */
	public function getConditions($form = null) {

		// build final condition
		require_once (WCF_DIR . 'lib/system/database/ConditionBuilder.class.php');
		$condition = new ConditionBuilder(false);

		$condition->add('messageTable.isActive = 1');
		$condition->add('messageTable.isDisabled = 0');

		// return sql condition
		return '(' . $condition->get() . ')';
	}

	/**
	 * @see SearchableMessageType::isAccessible()
	 */
	public function isAccessible() {
		return WCF::getUser()->getPermission('user.rmarketplace.canList');
	}

	/**
	 * @see SearchableMessageType::getJoins()
	 */
	public function getJoins() {
		parent::getJoins();
	}

	/**
	 * @see SearchableMessageType::getMessageFieldNames()
	 */
	public function getMessageFieldNames() {
		return array(
				'text',
				'country',
				'zipcode'
		);
	}

	/**
	 * Returns the database table name for this search type.
	 */
	public function getTableName() {
		return 'wcf' . WCF_N . '_rmarketplace_entries';
	}

	/**
	 * Returns the message id field name for this search type.
	 */
	public function getIDFieldName() {
		return 'entryID';
	}

	/**
	 * @see SearchableMessageType::getAdditionalData()
	 */
	public function getAdditionalData() {
		parent::getAdditionalData();
	}

	/**
	 * @see SearchableMessageType::getFormTemplateName()
	 */
	public function getFormTemplateName() {	// return 'searchMpEntry';
	}

	/**
	 * @see SearchableMessageType::getResultTemplateName()
	 */
	public function getResultTemplateName() {
		return 'searchResultMpEntry';
	}
}
?>