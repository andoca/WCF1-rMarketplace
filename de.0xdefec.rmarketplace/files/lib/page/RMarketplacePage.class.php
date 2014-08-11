<?php
// wcf imports
require_once (WCF_DIR . 'lib/page/AbstractPage.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/RMarketplaceList.class.php');
require_once (WCF_DIR . 'lib/page/util/menu/PageMenu.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * frontpage of the marketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplacePage extends AbstractPage {
	public $templateName = 'rmarketplace';
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
	 * only show new items
	 *
	 * @var boolean
	 */
	public $newItems = false;

	/**
	 * current page
	 *
	 * @var integer
	 */
	public $pageNum = 1;

	/**
	 * total amount of entries
	 *
	 * @var integer
	 */
	public $totalEntries = 0;

	public $type = null;
	public $tags;

	/**
	 * @see Page::readParameters();
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_GET['cat'])) $this->cat = intval($_GET['cat']);
		if (isset($_GET['newItems'])) $this->newItems = intval($_GET['newItems']);
		if (isset($_GET['pageNum'])) $this->pageNum = intval($_GET['pageNum']);
		if (isset($_GET['type']) && ($_GET['type'] == 'search' || $_GET['type'] == 'offer')) $this->type = StringUtil::trim($_GET['type']);
	}

	/**
	 * @see Page::readData();
	 */
	public function readData() {
		parent::readData();

		WCF::getUser()->checkPermission('user.rmarketplace.canList');

		$this->updateSession();

		$this->RMarketplaceList = new RMarketplaceList();
		$this->RMarketplaceList->onlyNew = $this->newItems;
		$this->RMarketplaceList->category = $this->cat;
		$this->RMarketplaceList->type = $this->type;

		$this->entries = $this->RMarketplaceList->get($this->pageNum);

		$this->pages = $this->RMarketplaceList->getPages();

		$this->totalEntries = $this->RMarketplaceList->getTotalEntries();

		$this->categories = new rmCategory(null);

		if ($this->cat !== 0) {
			$this->category = new rmCategory($this->cat);
			$this->categoryID = $this->category->catID;
		}
		else
			$this->categoryID = 0;

		if (defined('MODULE_TAGGING') && MODULE_TAGGING && RM_ENABLE_TAGS && RM_INDEX_ENABLE_TAGS) {
			$this->readTags();
		}
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!MODULE_RMARKETPLACE) {
			throw new IllegalLinkException();
		}
		parent::show();

		// handle action request
		switch ($this->action) {
			// disable notifications
			case 'disableNotifications' :
				WCF::getSession()->register('rMModerationNotificationDisabled', true);
				if (! isset($_REQUEST['ajax'])) {
					HeaderUtil::redirect('index.php' . SID_ARG_1ST);
					exit();
				}
				break;
		}
	}

	/**
	 * @see Page::assignVariables();
	 */
	public function assignVariables() {
		parent::assignVariables();
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.de.0xdefec.rmarketplace.header.menu');
		WCF::getTPL()->assign(array(

				'entries' => $this->entries,
				'totalEntries' => $this->totalEntries,
				//	'allEntries' => $this->allEntries,
				'categories' => $this->categories->getCategories($this->cat),
				'category' => $this->category,
				'newItems' => $this->newItems,
				'categoryID' => $this->categoryID,
				'categoryTree' => $this->categories->getCategoriesSelect(),
				'pages' => $this->pages,
				'pageNum' => $this->pageNum,
				'type' => $this->type,
				'tags' => $this->tags,
				'allowSpidersToIndexThisPage' => MP_SPIDER_INDEX
		));
	}

	/**
	 * Reads the tags
	 */
	protected function readTags() {
		// include files
		require_once (WCF_DIR . 'lib/data/tag/TagCloud.class.php');

		// get tags
		$tagCloud = new TagCloud(WCF::getSession()->getVisibleLanguageIDArray());
		$this->tags = $tagCloud->getTags();
	}

	public function updateSession() {
		// get last activity time


		// first try from session
		$rmLastActivityTime = WCF::getSession()->getVar('rmLastActivityTime');

		if (! $rmLastActivityTime) {
			// did not get it from session, try from DB if user is logged in
			if (WCF::getUser()->userID) {
				$sql = "SELECT lastActivityTime FROM wcf" . WCF_N . "_rmarketplace_visit
						WHERE userID = " . WCF::getUser()->userID;
				$row = WCF::getDB()->getFirstRow($sql);
				$rmLastActivityTime = $row['lastActivityTime'];
			}

			// construct a fallback if there is no data in db or user is not logged in
			if (! $rmLastActivityTime) {
				$rmLastActivityTime = TIME_NOW - (3600 * 24 * 3);
			}
		}

		// now check if we have to update the lastVisitTime
		// we do this if lastActivityTime is longer ago than session_timeout
		// **lastVisitTime** will then be the value we have in lastActivityTime
		if (($rmLastActivityTime + SESSION_TIMEOUT) < TIME_NOW) {
			// save the new lastVisitTime to Session and DB
			if (WCF::getUser()->userID) {
				$sql = "INSERT INTO wcf" . WCF_N . "_rmarketplace_visit
								(userID, lastVisitTime)
							VALUES
								(" . WCF::getUser()->userID . ", " . $rmLastActivityTime . ")
						ON DUPLICATE KEY UPDATE
							lastVisitTime = " . $rmLastActivityTime;
				WCF::getDB()->sendQuery($sql);
			}
			WCF::getSession()->register('rmLastVisitTime', $rmLastActivityTime);
		}
		else {
			// this is not a new visit, so we take the values from session or db
			$rmLastVisitTime = WCF::getSession()->getVar('rmLastVisitTime');
			// did not get it from session, try from DB if user is logged in
			if (! $rmLastVisitTime) {
				if (WCF::getUser()->userID) {
					$sql = "SELECT lastVisitTime FROM wcf" . WCF_N . "_rmarketplace_visit
							WHERE userID = " . WCF::getUser()->userID;
					$row = WCF::getDB()->getFirstRow($sql);
					$rmLastVisitTime = $row['lastVisitTime'];
				}
				if (! $rmLastVisitTime) $rmLastVisitTime = TIME_NOW - (3600 * 24 * 3);

				// save lastVisitTime to session
				WCF::getSession()->register('rmLastVisitTime', $rmLastVisitTime);
			}
		}

		// update lastActivityTime to current time
		if (WCF::getUser()->userID) {
			$sql = "INSERT INTO wcf" . WCF_N . "_rmarketplace_visit
							(userID, lastActivityTime)
						VALUES
							(" . WCF::getUser()->userID . ", " . TIME_NOW . ")
					ON DUPLICATE KEY UPDATE
						lastActivityTime = " . TIME_NOW;
			WCF::getDB()->sendQuery($sql);
		}
		WCF::getSession()->register('rmLastActivityTime', TIME_NOW);
	}
}
?>