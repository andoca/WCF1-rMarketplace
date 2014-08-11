<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/cronjobs/Cronjob.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntryEditor.class.php');
require_once (WCF_DIR . 'lib/data/user/UserProfile.class.php');

/**
 * Cronjob cleans up the marketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceCleanupCronjob implements Cronjob {

	/**
	 * @see Cronjob::execute()
	 */
	public function execute($data) {
		// First we check for entries that are older than max lifetime (they'll be deleted)
		if (MP_LIFETIME > 0) {
			$minTime = TIME_NOW - (MP_LIFETIME * 60 * 60 * 24);
			$sql = "SELECT entryID FROM wcf" . WCF_N . "_rmarketplace_entries WHERE time < " . $minTime;
			$query = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($query)) {
				$entry = new MpEntryEditor($row ['entryID']);
				$entry->delete(false);
			}
		}
		
		// now check for entries that will expire soon and notify users
		if (MP_ENTRY_INFO_TIME > 0) {
			$minTime = TIME_NOW - (MP_ENTRY_INFO_TIME * 60 * 60 * 24);
			$sql = "SELECT entryID, userID, pushCount FROM wcf" . WCF_N . "_rmarketplace_entries
					WHERE time < " . $minTime . "
					AND pushCount = notificationCount
					AND isDisabled = 0
					AND isActive = 1";
			$query = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($query)) {
				$owner = new UserProfile($row ['userID']);
				$entry = new MpEntryEditor($row ['entryID']);
				if (!$entry->isOld())
					$entry->sendExpireNotification();
			}
		}
		
		$sql = "UPDATE wcf".WCF_N."_rmarketplace_categories category 
					SET entries = (SELECT COUNT(*) FROM wcf1_rmarketplace_entries WHERE categoryID = category.catID);";
		WCF::getDB()->sendQuery($sql);
		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.rMarketplaceCategory.php');
	}
}
?>