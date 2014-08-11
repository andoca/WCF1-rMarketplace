<?php
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * reprensents a list of entries to display
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceList {
	/**
	 * the category to display entries from
	 *
	 * @var integer
	 */
	public $category = null;

	public $type = null;

	/**
	 * the userID ot display entries from
	 *
	 * @var integer
	 */
	public $userID = null;

	public $itemsPerPage = MP_ITEMSPP;
	public $inModeration = false;
	public $onlyNew = false;

	/**
	 * gets the list of entries for the given page
	 *
	 * @param integer $page
	 * @return array with MpEntry objects
	 */
	public function get($page = 1) {
		$entries = array();

		if ($page) $start = ($page - 1) * $this->itemsPerPage;

		$sql = "SELECT entryID FROM wcf" . WCF_N . "_rmarketplace_entries WHERE 1=1";
		if ($this->category !== null) {
			if (is_array($this->category) && count($this->category)) {
				foreach ( $this->category as $idx => $cat ) {
					$this->category[$idx] = intval($cat);
				}
				$sql .= " AND categoryID IN (" . implode(',', $this->category) . ")";
			}
			else if ($this->category > 0) {
				$catObj = new rmCategory($this->category);
				$childrenIDs = $catObj->getChildrenIDs();
				$additionalIDs = '';
				if (count($childrenIDs)) $additionalIDs = "," . implode(',', $childrenIDs);
				$sql .= " AND categoryID IN (" . $this->category . "" . $additionalIDs . ")";
			}
		}
		if (! WCF::getUser()->getPermission('mod.rmarketplace.canModerate')) {
			$sql .= " AND (isDisabled = 0 OR (userID = '" . WCF::getUser()->userID . "' AND userID != 0))";
		}
		if ($this->userID !== null) $sql .= " AND userID = '" . intval($this->userID) . "'";
		if ($this->inModeration) $sql .= " AND isDisabled = 1";
		if ($this->onlyNew) $sql .= " AND time > " . WCF::getSession()->getVar('rmLastVisitTime');
		if ($this->type) $sql .= " AND type='" . escapeString($this->type) . "'";
		$sql .= " ORDER BY time DESC";
		if ($page) $sql .= " LIMIT " . $start . ", " . $this->itemsPerPage;

		$query = WCF::getDB()->sendQuery($sql);
		while ( $row = WCF::getDB()->fetchArray($query) ) {
			$entries[] = new MpEntry($row['entryID']);
		}
		return $entries;
	}

	/**
	 * gets the list of entries in the given bounds
	 *
	 * @param float $LngLeft
	 * @param float $LngRight
	 * @param float $LatTop
	 * @param float $LatBottom
	 * @return array with MpEntry objects
	 */

	public function getBounds($LngLeft, $LngRight, $LatTop, $LatBottom) {
		$entries = array();

		$sql = "SELECT entryID FROM wcf" . WCF_N . "_rmarketplace_entries WHERE 1=1";
		$sql .= " AND lat <= " . floatval($LatTop);
		$sql .= " AND lat >= " . floatval($LatBottom);
		$sql .= " AND lng <= " . floatval($LngRight);
		$sql .= " AND lng >= " . floatval($LngLeft);
		if ($this->category) {
			$catObj = new rmCategory($this->category);
			$childrenIDs = $catObj->getChildrenIDs();
			$additionalIDs = '';
			if (count($childrenIDs)) $additionalIDs = "," . implode(',', $childrenIDs);
			$sql .= " AND categoryID IN (" . $this->category . "" . $additionalIDs . ")";
		}
		if ($this->type) {
			$sql .= " AND type = '" . escapeString($this->type) . "'";
		}

		$query = WCF::getDB()->sendQuery($sql);
		while ( $row = WCF::getDB()->fetchArray($query) ) {
			$entries[] = new MpEntry($row['entryID']);
		}

		return $entries;
	}

	/**
	 * gets the amount of entries in the whole db for one cat and its children
	 *
	 * @return integer
	 * @todo implement a way to get also disabled entries for statistics
	 */
	public function getTotalEntries() {
		$additionalSql = '';
		if ($this->category !== null && $this->category > 0) {
			$cat = new rmCategory(intval($this->category));
			$childrenIDs = $cat->getChildrenIDs();
			$additionalIDs = '';
			if (count($childrenIDs)) $additionalIDs = "," . implode(',', $childrenIDs);
			$additionalSql = " AND categoryID IN (" . $cat->catID . "" . $additionalIDs . ")";
		}

		if (! WCF::getUser()->getPermission('mod.rmarketplace.canModerate')) {
			$additionalSql .= " AND (isDisabled = 0 OR (userID = '" . WCF::getUser()->userID . "' AND userID != 0))";
		}
		if ($this->userID !== null) $additionalSql .= " AND userID = '" . intval($this->userID) . "'";
		if ($this->inModeration) $additionalSql .= " AND isDisabled = 1";
		if ($this->onlyNew) $additionalSql .= " AND time > " . WCF::getSession()->getVar('rmLastVisitTime');
		if ($this->type) $additionalSql .= " AND type='" . escapeString($this->type) . "'";

		$sql = "SELECT count(*) as anzahl FROM wcf" . WCF_N . "_rmarketplace_entries WHERE 1=1 " . $additionalSql . "";

		$row = WCF::getDB()->getFirstRow($sql);
		return $row['anzahl'];
	}

	/**
	 * gets the amount of pages to display
	 *
	 * @return integer
	 */
	public function getPages() {
		$sql = "SELECT count(*) as anzahl FROM wcf" . WCF_N . "_rmarketplace_entries WHERE 1=1 ";

		if (! WCF::getUser()->getPermission('mod.rmarketplace.canModerate')) {
			$sql .= " AND (isDisabled = 0 OR (userID = '" . WCF::getUser()->userID . "' AND userID != 0))";
		}
		if ($this->userID !== null) $sql .= " AND userID = '" . intval($this->userID) . "'";
		if ($this->inModeration) $sql .= " AND isDisabled = 1";
		if ($this->onlyNew) $sql .= " AND time > " . WCF::getSession()->getVar('rmLastVisitTime');
		if ($this->type) $sql .= " AND type='" . escapeString($this->type) . "'";

		if ($this->category !== null && $this->category > 0) {
			$cat = new rmCategory(intval($this->category));
			$childrenIDs = $cat->getChildrenIDs();
			$additionalIDs = '';
			if (count($childrenIDs)) $additionalIDs = "," . implode(',', $childrenIDs);
			$sql .= " AND categoryID IN (" . $cat->catID . "" . $additionalIDs . ")";
		}

		$row = WCF::getDB()->getFirstRow($sql);

		if (! $row['anzahl']) return 1;

		$pages = ceil($row['anzahl'] / $this->itemsPerPage);
		return $pages;
	}

}

?>