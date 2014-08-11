<?php
// wcf imports
require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');

/**
 * implements the amount of active entries of a user in the message sidebar
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace.userprofile
 */
class MessageSidebarRMarketplaceListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {

		if (WCF::getUser()->getPermission('user.rmarketplace.canList') && SIDEBAR_SHOW_MARKETPLACE_ENTRIES) {
			foreach ( $eventObj->messageSidebars as $id => $sidebar ) {
				if ($sidebar->getUser()->userID != 0) {
					$userEntries = 0;

					$sql = "SELECT count(*) as entries FROM wcf" . WCF_N . "_rmarketplace_entries WHERE userID = '" . intval($sidebar->getUser()->userID) . "' AND isActive=1 AND isDisabled=0";
					if (MP_ENTRY_OLD_TIME != 0) {
						$maxTime = TIME_NOW - (MP_ENTRY_OLD_TIME * 60 * 60 * 24);
						$sql .= " AND time > '" . $maxTime . "'";
					}

					$row = WCF::getDB()->getFirstRow($sql);
					$userEntries = $row['entries'];

					if ($userEntries > 0) {
						$sidebar->userCredits = array_merge($sidebar->userCredits, array(
								array(
										'name' => WCF::getLanguage()->get('wcf.message.sidebar.rmarketplace'),
										'value' => StringUtil::formatInteger($userEntries),
										'url' => 'index.php?page=User&amp;userID=' . $sidebar->getUser()->userID . SID_ARG_2ND . "#rmarketplace"
								)
						));
					}
				}
			}
		}

	}
}
?>