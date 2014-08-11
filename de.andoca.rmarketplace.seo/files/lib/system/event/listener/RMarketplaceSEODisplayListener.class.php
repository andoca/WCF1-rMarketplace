<?php
//wcf imports
require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');
require_once (WCF_DIR . 'lib/data/page/seo/SEOUtil.class.php');
//own imports
require_once (WCF_DIR . 'lib/data/rmarketplace/seo/RMarketplaceSEORewriter.class.php');

/**
 * Parses SEO links in output.
 * 
 * @author		BBLL
 * @copyright	2005 - 2010
 * @license		Commercial
 * @package		de.bbll.rmarketplace.seo
 * @subpackage	system.event.listener
 * @category 	RMarketplace
 */
class RMarketplaceSEODisplayListener implements EventListener {

	protected $buffer = null;

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		// Check SEO
		if (SEO_ENABLE && !SEOUtil::isExcludedPage()) {
			// Check Event
			if ($eventName == 'shouldDisplay') {
				// Start output buffering
				ob_start(array (
						$this, 
						'formatOutput' 
				));
			}
		}
	}

	/**
	 * Parses the given output.
	 * 
	 * @access	protected
	 * @param	string		$output
	 * @param	integer		$status
	 * @return	string
	 */
	public function formatOutput($output, $status) {
		if ($status & PHP_OUTPUT_HANDLER_START) {
			$SEO = new RMarketplaceSEORewriter();
			$this->buffer = $SEO->rewrite($output);
		}
		if ($status & PHP_OUTPUT_HANDLER_END) {
			return $this->buffer;
		}
	}
}
?>