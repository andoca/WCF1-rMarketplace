<?php
// wcf imports
require_once (WCF_DIR . 'lib/page/RMarketplacePage.class.php');

/**
 * moderation page of the marketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceModerationPage extends RMarketplacePage {

	/**
	 * @see Page::readParameters();
	 */
	public function readParameters() {
		// call readParameters event
		//EventHandler::fireAction($this, 'readParameters');
		parent::readParameters();
	}

	/**
	 * @see Page::assignData();
	 */
	public function readData() {
		// call readData event
		EventHandler::fireAction($this, 'readData');

		WCF::getUser()->checkPermission('mod.rmarketplace.canModerate');

		$this->RMarketplaceList = new RMarketplaceList();
		$this->RMarketplaceList->category = $this->cat;
		$this->RMarketplaceList->inModeration = true;
		$this->entries = $this->RMarketplaceList->get($this->pageNum);
		$this->allEntries = $this->RMarketplaceList->get(false);
		$this->pages = $this->RMarketplaceList->getPages();

		$this->totalEntries = $this->RMarketplaceList->getTotalEntries();

		$this->categories = new rmCategory(null);

		if ($this->cat !== 0) {
			$this->category = new rmCategory($this->cat);
			$this->categoryID = $this->category->catID;
		}
		else
			$this->categoryID = 0;
	}

	/**
	 * @see Page::assignVariables();
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
				'inModeration' => true
		));
	}
}
?>