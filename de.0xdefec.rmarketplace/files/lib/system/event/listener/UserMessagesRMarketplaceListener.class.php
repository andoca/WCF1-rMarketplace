<?php
// wcf imports
require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');

/**
 * notifies rmarketplace mods about new entries to moderate
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class UserMessagesRMarketplaceListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (WCF::getUser()->getPermission('mod.rmarketplace.canModerate')) {
			if (WCF::getSession()->getVar('rMModerationNotificationDisabled') !== true) {

				// get entries
				require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');

				$RMarketplaceList = new RMarketplaceList();
				$RMarketplaceList->itemsPerPage = 50;
				$RMarketplaceList->inModeration = true;
				$entries = $RMarketplaceList->get();

				$count = count($entries);
				if ($count > 0) {

					WCF::getTPL()->assign(array(

							'uMentries' => $entries,
							'uMmarketplaceEntries' => $count
					));
					WCF::getTPL()->append('userMessages', WCF::getTPL()->fetch('userMessagesMarketplace'));
				}
			}
		}

		// additionalUserMenuItems
		if (USERMENU_SHOW_MARKETPLACE_ENTRIES && WCF::getUser()->getPermission('user.rmarketplace.canList')) {
			// get lastVisitTime
			$rmLastVisitTime = WCF::getSession()->getVar('rmLastVisitTime');

			if (! $rmLastVisitTime) {
				if (WCF::getUser()->userID) {
					$sql = "SELECT lastVisitTime FROM wcf" . WCF_N . "_rmarketplace_visit
							WHERE userID = " . WCF::getUser()->userID;
					$row = WCF::getDB()->getFirstRow($sql);
					$rmLastVisitTime = $row['lastVisitTime'];
				}
				if (! $rmLastVisitTime) $rmLastVisitTime = TIME_NOW - (3600 * 24 * 3);

				// save lastVisitTime to session
				WCF::getSession()->register('rmLastVisitTime', $rmLastVisitTime);
			}

			$fetchNew = false;
			$currentNew = WCF::getSession()->getVar('rmCurrentNew');
			if ($currentNew) {
				if ($currentNew['lastUpdateTime'] > $rmLastVisitTime || (TIME_NOW - $currentNew['lastUpdateTime']) > 0) {
					// fetch new data
					$fetchNew = true;
				}
			}
			else
				$fetchNew = true;

			if ($fetchNew) {
				$sql = "SELECT count(*) as items FROM wcf" . WCF_N . "_rmarketplace_entries WHERE time > " . $rmLastVisitTime . " AND isDisabled = 0 AND isActive = 1";
				$row = WCF::getDB()->getFirstRow($sql);
				$currentNew = array(
						'lastUpdateTime' => TIME_NOW,
						'count' => $row['items']
				);
				WCF::getSession()->register('rmCurrentNew', $currentNew);
			}

			WCF::getTPL()->assign(array(
					'rmEntriesNew' => $currentNew['count']
			));
			if($currentNew['count'] > 0 || USERMENU_SHOW_MARKETPLACE_ENTRIES_ALSO_WHEN_NOT_NEW) WCF::getTPL()->append('additionalUserMenuItems', WCF::getTPL()->fetch('rmarketplaceHeaderInfo'));
		}
	}
}
?>