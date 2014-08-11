<?php
// wbb imports
require_once (WBB_DIR . 'lib/data/boxes/PortalBox.class.php');
require_once (WBB_DIR . 'lib/data/boxes/StandardPortalBox.class.php');
//wcf imports
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');

/**
 * This box shows rMarketplaceentries
 */
class RMarketplaceBox extends PortalBox implements StandardPortalBox {

	public $rMentries = array ();

	/**
	 * @see StandardPortalBox::readData()
	 */
	public function readData() {
		foreach ($this->cacheData as $entryID) {
			$entry = new MpEntry($entryID ['entryID']);
			$this->rMentries [] = $entry;
		}
		if (!count($this->rMentries))
			$this->empty = true;
	}

	/**
	 * @see StandardPortalBox::getTemplateName()
	 */
	public function getTemplateName() {
		return 'rmarketplacebox';
	}
}
?>