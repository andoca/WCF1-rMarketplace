<?php
// wcf imports
require_once (WCF_DIR . 'lib/form/AbstractForm.class.php');
require_once (WCF_DIR . 'lib/data/user/UserProfile.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * form to deliver data to external marketplace sources
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace.external
 */
class RMarketplaceExternalForm extends AbstractForm {
	public $templateName = 'rmarketplaceExternal';
	public $RMarketplaceList = null;

	/**
	 * selected category
	 *
	 * @var string
	 */
	public $cat = null;

	/**
	 * selected userID
	 */
	public $userID = null;

	/**
	 * object of the selected category
	 *
	 * @var string
	 */
	public $category = null;

	public $items = 4;

	public $user = null;

	/**
	 * total amount of entries
	 *
	 * @var integer
	 */
	public $totalEntries = 0;

	public $type = null;

	/**
	 * @see Page::readParameters();
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['cat']) && $_REQUEST['cat']) $this->cat = $_REQUEST['cat'];
		if (isset($_REQUEST['items']) && $_REQUEST['items']) $this->items = intval($_REQUEST['items']);
		if (isset($_REQUEST['userID']) && $_REQUEST['userID']) $this->userID = StringUtil::trim($_REQUEST['userID']);
		if (isset($_REQUEST['type']) && ($_REQUEST['type'] == 'search' || $_REQUEST['type'] == 'offer')) $this->type = StringUtil::trim($_REQUEST['type']);

		if ($this->cat) {
			$this->cat = explode(',', $this->cat);
			$this->cat = ArrayUtil::trim($this->cat);
		}

	}

	/**
	 * @see Page::assignData();
	 */
	public function readData() {
		parent::readData();

		if (! MP_EXTERNAL_ENABLED) throw new IllegalLinkException();

		$this->RMarketplaceList = new RMarketplaceList();
		$this->RMarketplaceList->category = $this->cat;
		$this->RMarketplaceList->type = $this->type;
		$this->RMarketplaceList->userID = $this->userID;

		if ($this->userID) $this->user = new UserProfile($this->userID);

		if ($this->items > MP_EXTERNAL_MAX_ITEMS) $this->items = MP_EXTERNAL_MAX_ITEMS;
		$this->RMarketplaceList->itemsPerPage = $this->items;

		$this->entries = $this->RMarketplaceList->get();
	}

	/**
	 * @see Page::assignVariables();
	 */
	public function assignVariables() {
		parent::assignVariables();
		// set active header menu item
		WCF::getTPL()->assign(array(

				'entries' => $this->entries,
				'type' => $this->type,
				'cat' => $this->cat,
				'user' => $this->user
		));
	}
}
?>