<?php
// wcf imports
require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');

/**
 * implements the last entries of a user in his profile
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace.userprofile
 */
class UserPageRMarketplaceListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (PROFILE_SHOW_MARKETPLACE_ENTRIES == 1 && WCF::getUser()->getPermission('user.rmarketplace.canList')) {

			// get entries
			require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');

			$RMarketplaceList = new RMarketplaceList();
			$RMarketplaceList->userID = $eventObj->frame->getUserID();
			$entries = $RMarketplaceList->get();

			$count = count($entries);
			if ($count > 0) {

				WCF::getTPL()->assign(array(

						'user' => $eventObj->frame->getUser(),
						'entries' => $entries,
						'marketplaceEntries' => $count
				));
				WCF::getTPL()->append('additionalContent3', WCF::getTPL()->fetch('userProfileMarketplace'));
			}
		}

	}
}
?>