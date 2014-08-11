<?php
//wcf imports
require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');

//own imports
//none
/**
 * Parses SEO URLs in messages.
 * 
 * @author		BBLL
 * @copyright	2005 - 2010
 * @license		Commercial
 * @package		de.bbll.rmarketplace.seo
 * @subpackage	system.event.listener
 * @category 	RMarketplace
 */
class RMarketplaceSEOURLParserListener implements EventListener {

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (SEO_ENABLE) {
			// empty
		}
	}
}
?>