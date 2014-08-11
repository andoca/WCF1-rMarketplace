<?php
//wcf imports
require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');
require_once (WCF_DIR . 'lib/data/page/seo/SEOUtil.class.php');
//own imports
require_once (WCF_DIR . "lib/data/rmarketplace/rmcategory/rmCategory.class.php");

/**
 * Gets the category id to an given category name
 * 
 * @author		BBLL
 * @copyright	2005 - 2010
 * @license		Commercial
 * @package		de.bbll.rmarketplace.seo
 * @subpackage	system.event.listener
 * @category 	RMarketplace
 */
class RMarketplaceSEOCategoryListener implements EventListener {

	protected $categories = array ();

	protected $categoryName = "";

	/**
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		// Check if an execute is required
		if (isset($_REQUEST ['catName']) and !isset($_REQUEST ['cat'])) {
			// Save category name
			$this->catName = $_REQUEST ['catName'];
			// Get all categories
			$categoriesObject = new rmCategory();
			$this->categories = $categoriesObject->getCategories();
			// Foreach category
			foreach ($this->categories as $item) {
				// Compare given name with loaded name
				if ($_REQUEST ['catName'] == SEOUtil::formatString(WCF::getLanguage()->getDynamicVariable($item->catName))) {
					// If Given name = loaded name; Save and return
					$_REQUEST ['catName'] = $item->catName;
					return;
				}
			}
		}
	}
}
?>