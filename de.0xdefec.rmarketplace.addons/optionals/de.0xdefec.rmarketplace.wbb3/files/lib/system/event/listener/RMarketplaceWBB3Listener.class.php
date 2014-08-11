<?php
// wcf imports
require_once (WCF_DIR . 'lib/system/event/EventListener.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * displays the last 5 entries from the rmarketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace.wbb3
 */
class RMarketplaceWBB3Listener implements EventListener {
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
	 * @see EventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName) {
		if (@WCF::getRequest()->page != 'IndexPage' || ! RMARKETPLACE_WBB3_SHOW_LAST) return;
		if (WCF::getUser()->getPermission('user.rmarketplace.canList')) {
			$this->RMarketplaceList = new RMarketplaceList();
			$this->RMarketplaceList->category = $this->cat;
			$this->RMarketplaceList->itemsPerPage = 5;
			$this->RMarketplaceList->type = $this->type;

			$this->entries = $this->RMarketplaceList->get(1);
			$status = 1;

			if (WCF::getUser()->userID != 0) {
				$status = intval(WCF::getUser()->rmarketplaceTopList);
			}
			else {
				if (WCF::getSession()->getVar('rmarketplaceTopList') != false) {
					$status = WCF::getSession()->getVar('rmarketplaceTopList');
				}
			}

			WCF::getTPL()->assign(array(
					'rmEntries' => $this->entries,
					'rmCategory' => $this->category,
					'rmType' => $this->type,
					'rmarketplaceTopListStatus' => $status
			));

			// append content of template file
			WCF::getTPL()->append('additionalTopContents', WCF::getTPL()->fetch('rmarketplaceWBB3TopInclude'));
		}
	}
}
?>