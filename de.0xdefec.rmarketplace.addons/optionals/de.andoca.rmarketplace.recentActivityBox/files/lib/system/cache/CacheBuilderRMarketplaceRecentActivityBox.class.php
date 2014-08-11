<?php
// wcf imports
require_once (WCF_DIR . 'lib/system/cache/CacheBuilder.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * Caches rMarketplace Entries for the recent activity box.
 */
class CacheBuilderRMarketplaceRecentActivityBox implements CacheBuilder {

	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$rMarketplaceList = new RMarketplaceList();
		$rMarketplaceList->itemsPerPage = 5;
		
		$entries = $rMarketplaceList->get(1);
		
		return $entries;
	}
}
?>