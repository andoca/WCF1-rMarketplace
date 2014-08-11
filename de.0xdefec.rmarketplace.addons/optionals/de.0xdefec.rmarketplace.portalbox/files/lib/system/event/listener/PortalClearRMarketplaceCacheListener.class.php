<?php
// wcf imports
require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');

// wbb imports
require_once (WBB_DIR . 'lib/data/boxes/PortalBox.class.php');

/**
 * Clears the box cache if fired
 *
 */
class PortalClearRMarketplaceCacheListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		$box = PortalBox::getBoxByName('rmarketplacebox');
		$box->clearDataCache();
		
		$box = PortalBox::getBoxByName('rmarketplacesidebox');
		$box->clearDataCache();
	}
}
?>