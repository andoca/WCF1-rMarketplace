<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/page/location/Location.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * RMarketplaceCategoryLocation is an implementation of Location for the RMarketplace(Category) page.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceCategoryLocation implements Location {
	public $categories = null;

	/**
	 * @see Location::cache()
	 */
	public function cache($location, $requestURI, $requestMethod, $match) {}

	/**
	 * @see Location::get()
	 */
	public function get($location, $requestURI, $requestMethod, $match) {

		$categoryID = $match[1];
		if (!$categoryID) return '';

		if (! isset($this->categories[$categoryID])) {
			$this->categories[$categoryID] = new rmCategory(intval($categoryID));
			if ($this->categories[$categoryID]->catID == 0) return '';
		}

		if (! isset($this->categories[$categoryID]) || ! WCF::getUser()->getPermission('user.rmarketplace.canList')) {
			return '';
		}

		return WCF::getLanguage()->get($location['locationName'], array(
				'$category' => '<a href="index.php?page=RMarketplace&amp;cat=' . $this->categories[$categoryID]->catID . SID_ARG_2ND . '">' . WCF::getLanguage()->get($this->categories[$categoryID]->catName) . '</a>'
		));
	}
}
?>