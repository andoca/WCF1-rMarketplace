<?php
// wbb imports
require_once (WBB_DIR . 'lib/data/boxes/PortalBox.class.php');
require_once (WBB_DIR . 'lib/data/boxes/StandardPortalBox.class.php');
//wcf imports
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');

/**
 * This box shows rMarketplaceentries
 */
class RMarketplaceSideBox extends PortalBox implements StandardPortalBox {

	public $rMentries = array ();

	/**
	 * @see StandardPortalBox::readData()
	 */
	public function readData() {
		$this->rMentries ['search'] = array ();
		$this->rMentries ['offer'] = array ();
		
		foreach ($this->cacheData ['search'] as $entryID) {
			$entry = new MpEntry($entryID ['entryID']);
			$this->rMentries ['search'] [] = $entry;
		}
		
		foreach ($this->cacheData ['offer'] as $entryID) {
			$entry = new MpEntry($entryID ['entryID']);
			$this->rMentries ['offer'] [] = $entry;
		}
		
		if (!count($this->rMentries ['search']) && !count($this->rMentries ['offer']))
			$this->empty = true;
	}

	/**
	 * @see StandardPortalBox::getTemplateName()
	 */
	public function getTemplateName() {
		return 'rmarketplacesidebox';
	}
}
?>