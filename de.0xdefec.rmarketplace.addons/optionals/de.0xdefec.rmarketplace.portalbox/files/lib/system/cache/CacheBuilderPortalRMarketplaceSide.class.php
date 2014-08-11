<?php
require_once (WCF_DIR . 'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches the rmarketplace box items
 *
 */
class CacheBuilderPortalRMarketplaceSide implements CacheBuilder {

	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		$data = array ();
		
		if (PORTAL_RMARKETPLACESIDEBOXITEMS_RANDOM) {
			$orderBy = "ORDER BY RAND()";
		} else {
			$orderBy = "ORDER BY time DESC";
		}
		$types = array (
				'search', 
				'offer' 
		);
		
		foreach ($types as $type) {
			
			$data [$type] = array ();
			
			$sql = "SELECT entryID FROM wcf" . WCF_N . "_rmarketplace_entries
			WHERE isDisabled = 0 AND type = '" . $type . "'
			" . $orderBy . "
			LIMIT 0, " . PORTAL_LIMIT_RMARKETPLACESIDE;
			
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$data [$type] [] = $row;
			}
		}
		
		return $data;
	}
}
?>