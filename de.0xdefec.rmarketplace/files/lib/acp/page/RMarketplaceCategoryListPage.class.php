<?php
// wcf imports
require_once (WCF_DIR . 'lib/page/AbstractPage.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * Page to list the rMarketplace Categories
 *
 * @package de.0xdefec.rmarketplace
 * @author Andreas Diendorfer
 * @copyright 20.11.2008 - Andreas Diendorfer
 */
class RMarketplaceCategoryListPage extends AbstractPage {
	public $templateName = 'RMarketplaceCategoryList';
	public $activeMenuItem = 'wcf.acp.rmarketplace';
	public $neededPermissions = 'admin.rmarketplace.canAdministrate';

	public $moved = false;

	/**
	 * Array with all available indexAds
	 *
	 * @var array
	 */
	protected $categories = array();

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_GET['moved'])) $this->moved = true;
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		$cats = new rmCategory(null);
		$this->categories = $cats->getCategoriesSelect();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(array(
				'categories' => $this->categories,
				'moved' => $this->moved
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem($this->activeMenuItem);

		// check permission
		WCF::getUser()->checkPermission($this->neededPermissions);

		parent::show();
	}
}
?>