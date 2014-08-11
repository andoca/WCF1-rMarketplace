<?php
// wcf imports
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');
require_once(WCF_DIR.'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * Caches rmarketplace categories
 *
 */
class CacheBuilderRMarketplaceCategory implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data = array('categories' => array(), 'categoryStructure' => array());

		// categories and structure
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_rmarketplace_categories
			ORDER BY	catParent ASC, catOrder ASC";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$data['categories'][$row['catID']] = new rmCategory(null, $row);
			$data['categoryStructure'][$row['catParent']][] = $row['catID'];
		}

		return $data;
	}
}
?>