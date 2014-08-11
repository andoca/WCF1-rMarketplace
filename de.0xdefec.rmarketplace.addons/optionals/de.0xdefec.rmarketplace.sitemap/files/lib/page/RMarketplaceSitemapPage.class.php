<?php
// wcf imports
require_once (WCF_DIR . 'lib/page/AbstractFeedPage.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * Generates the xml sitemap for the marketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace.feed
 */
class RMarketplaceSitemapPage extends AbstractFeedPage {
	public $RMarketplaceList = null;

	/**
	 * selected category
	 *
	 * @var integer
	 */
	public $cat = 0;

	/**
	 * object of the selected category
	 *
	 * @var string
	 */
	public $category = null;

	/**
	 * @see Page::readParameters();
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_GET['cat'])) $this->cat = intval($_GET['cat']);
	}

	/**
	 * @see Page::assignData();
	 */
	public function readData() {
		parent::readData();

		WCF::getUser()->checkPermission('user.rmarketplace.canList');

		$this->RMarketplaceList = new RMarketplaceList();
		$this->RMarketplaceList->category = $this->cat;
		$this->entries = $this->RMarketplaceList->get(null);

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

				'entries' => $this->entries,
				'category' => $this->category,
				'categoryID' => $this->categoryID
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show(); // send header


		@header('Content-Type: text/xml; charset=' . CHARSET);

		// send content
		WCF::getTPL()->display('rmarketplaceSitemap', false);
	}
}
?>