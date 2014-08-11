<?php
// wcf imports
require_once (WCF_DIR . 'lib/page/AbstractFeedPage.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * Generates the rss/atom feeds for the marketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace.feed
 */
class RMarketplaceFeedPage extends AbstractFeedPage {
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

	public $type = null;

	/**
	 * @see Page::readParameters();
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_GET['cat'])) $this->cat = intval($_GET['cat']);
		if (isset($_GET['type']) && ($_GET['type'] == 'search' || $_GET['type'] == 'offer')) $this->type = StringUtil::trim($_GET['type']);
	}

	/**
	 * @see Page::assignData();
	 */
	public function readData() {
		parent::readData();

		WCF::getUser()->checkPermission('user.rmarketplace.canList');

		$this->RMarketplaceList = new RMarketplaceList();
		$this->RMarketplaceList->category = $this->cat;
		$this->RMarketplaceList->itemsPerPage = MP_FEED_ITEMS;
		$this->RMarketplaceList->type = $this->type;

		$this->entries = $this->RMarketplaceList->get(1);

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
				'categoryID' => $this->categoryID,
				'type' => $this->type
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		parent::show();

		if (! MP_FEED_ACTIVE) throw new IllegalLinkException();

		// send content
		WCF::getTPL()->display(($this->format == 'atom' ? 'feedAtomRmarketplace' : 'feedRss2Rmarketplace'), false);
	}
}
?>