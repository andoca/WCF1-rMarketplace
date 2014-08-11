<?php
require_once (WCF_DIR . 'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the rmarketplace box items
 *
 */
class CacheBuilderPortalRMarketplace implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data = array();

		if (PORTAL_RMARKETPLACEBOXITEMS_RANDOM) {
			$orderBy = "ORDER BY RAND()";
		}
		else {
			$orderBy = "ORDER BY time DESC";
		}

		$sql = "SELECT entryID FROM wcf" . WCF_N . "_rmarketplace_entries
			WHERE isDisabled = 0
			" . $orderBy . "
			LIMIT 0, " . PORTAL_LIMIT_RMARKETPLACE;

		$result = WCF::getDB()->sendQuery($sql);
		while ( $row = WCF::getDB()->fetchArray($result) ) {
			$data[] = $row;
		}

		return $data;
	}
}
?>