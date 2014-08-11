<?php
// wcf imports
require_once (WCF_DIR . 'lib/page/AbstractPage.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');
require_once (WCF_DIR . 'lib/page/util/menu/PageMenu.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * map of the marketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace.map
 */
class RMarketplaceMapPage extends AbstractPage {
	public $templateName = 'rmarketplaceMap';
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
		if (! MP_GMAP_LARGE_ENABLED) throw new IllegalLinkException();
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
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.de.0xdefec.rmarketplace.header.menu');
		WCF::getTPL()->assign(array(

				'categories' => $this->categories->getCategories($this->cat),
				'category' => $this->category,
				'categoryID' => $this->categoryID,
				'categoryTree' => $this->categories->getCategoriesSelect()
		));
	}
}
?>