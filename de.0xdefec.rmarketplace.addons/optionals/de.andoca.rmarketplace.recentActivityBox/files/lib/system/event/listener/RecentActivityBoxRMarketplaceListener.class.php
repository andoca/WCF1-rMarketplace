<?php
// wcf imports
require_once(WCF_DIR.'lib/system/event/EventListener.class.php');

// wbb imports
require_once(WBB_DIR.'lib/data/page/recentActivityBox/RecentActivityBoxManager.class.php');
require_once(WBB_DIR.'lib/data/page/recentActivityBox/RMarketplaceRecentActivityBox.class.php');

class RecentActivityBoxRMarketplaceListener implements EventListener {
	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		RecentActivityBoxManager::getInstance()->addBox(new RMarketplaceRecentActivityBox());
	}
}
?>