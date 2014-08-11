<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');

// wbb imports
require_once (WBB_DIR . 'lib/data/page/recentActivityBox/RecentActivityBox.class.php');

/**
 * Implementation of RecentActivityBox to show the lastest rMarketplace entries.
 * 
 */
class RMarketplaceRecentActivityBox implements RecentActivityBox {

	/**
	 * Cached entries
	 * @var array<mpEntry>
	 */
	protected $cachedEntries = null;

	/**
	 * number of new entries
	 * @var integer
	 */
	protected $newItems = 0;

	/**
	 * Loads the cache.
	 */
	protected function initCache() {
		$this->cachedEntries = array ();
		
		// register cache
		WCF::getCache()->addResource('rmarketplaceRecentActivityBox', WBB_DIR . 'cache/cache.rmarketplaceRecentActivityBox.php', WBB_DIR . 'lib/system/cache/CacheBuilderRMarketplaceRecentActivityBox.class.php', 0, 180);
		
		// get cache
		$cachedEntries = WCF::getCache()->get('rmarketplaceRecentActivityBox');
		
		if (count($cachedEntries)) {
			foreach ($cachedEntries as $rmEntry) {
				$this->cachedEntries [] = $rmEntry;
				if ($rmEntry->isNew())
					$this->newItems++;
			}
		}
	}

	/**
	 * @see RecentActivityBox::getIdentifier()
	 */
	public function getIdentifier() {
		return 'de.0xdefec.rmarketplace.title';
	}

	/**
	 * @see RecentActivityBox::getTitle()
	 */
	public function getTitle() {
		return WCF::getLanguage()->get('de.0xdefec.rmarketplace.title');
	}

	/**
	 * @see RecentActivityBox::getNewItems()
	 */
	public function getNewItems() {
		return $this->newItems;
	}

	/**
	 * @see RecentActivityBox::hasContent()
	 */
	public function hasContent() {
		if ($this->cachedEntries === null)
			$this->initCache();
		
		if (count($this->cachedEntries)) {
			return true;
		}
		return false;
	}

	/**
	 * @see RecentActivityBox::getContent()
	 */
	public function getContent() {
		if ($this->hasContent()) {
			WCF::getTPL()->assign('rmEntries', $this->cachedEntries);
			return WCF::getTPL()->fetch('rmarketplaceRecentActivityBox');
		}
		return '';
	}
}
?>