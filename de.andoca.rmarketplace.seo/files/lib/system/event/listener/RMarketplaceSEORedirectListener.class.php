<?php
//wcf imports
require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');
//own imports
require_once (WCF_DIR . 'lib/data/rmarketplace/seo/RMarketplaceSEORedirecter.class.php');

/**
 * Redircets SEO links
 * 
 * @author		BBLL
 * @copyright	2005 - 2010
 * @license		Commercial
 * @package		de.bbll.rmarketplace.seo
 * @subpackage	system.event.listener
 * @category 	RMarketplace
 */
class RMarketplaceSEORedirectListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (SEO_ENABLE) {
			if (!isset($_SERVER ['REQUEST_URI'])) {
				return;
			}
			// Start redirecting
			$SEO = new RMarketplaceSEORedirecter();
			$SEO->redirect(basename($_SERVER ['REQUEST_URI']), get_class($eventObj));
		}
	}
}
?>