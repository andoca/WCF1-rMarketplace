<?php
// wcf imports
require_once (WCF_DIR . 'lib/page/AbstractPage.class.php');
require_once (WCF_DIR . 'lib/page/util/menu/PageMenu.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * rmarketpalce widget generator
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace.widget
 */
class RMarketplaceWidgetPage extends AbstractPage {
	public $templateName = 'rmarketplaceWidget';
	/**
	 * @see Page::readParameters();
	 */
	public function readParameters() {
		parent::readParameters();
	}

	/**
	 * @see Page::assignData();
	 */
	public function readData() {
		parent::readData();

		if (! MP_EXTERNAL_ENABLED) throw new IllegalLinkException();
		WCF::getUser()->checkPermission('user.rmarketplace.canViewExternal');

		$this->categories = new rmCategory(null);
	}

	/**
	 * @see Page::assignVariables();
	 */
	public function assignVariables() {
		parent::assignVariables();
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.de.0xdefec.rmarketplace.header.menu');

		WCF::getTPL()->assign(array(
				'categoryTree' => $this->categories->getCategoriesSelect()
		));
	}
}
?>