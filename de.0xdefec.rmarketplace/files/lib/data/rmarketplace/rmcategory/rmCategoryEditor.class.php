<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * edits a category
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class rmCategoryEditor extends rmCategory {

	/**
	 * @see rmCategory::__construct()
	 */
	public function __construct($categoryID, $row = null, $cacheObject = null, $useCache = true) {
		if ($useCache)
			parent::__construct($categoryID, $row, $cacheObject);
		else {
			$sql = "SELECT	*
				FROM	wcf" . WCF_N . "_rmarketplace_categories
				WHERE	catID = " . $categoryID;
			$row = WCF::getDB()->getFirstRow($sql);
			parent::__construct(null, $row);
		}
	}

	/**
	 * Adds a new category
	 *
	 * @param string $catName
	 * @param integer $catOrder
	 * @param integer $catParent
	 * @param string $catDescription
	 * @param string $catIcon
	 */
	public static function add($catName, $catOrder, $catParent, $catDescription, $catIcon, $catInfo) {
		$sql = "INSERT INTO wcf" . WCF_N . "_rmarketplace_categories
			(
				catName,
				catOrder,
				catParent,
				catDescription,
				catInfo,
				catIcon
			) VALUES (
				'" . escapeString($catName) . "',
				'" . intval($catOrder) . "',
				'" . intval($catParent) . "',
				'" . escapeString($catDescription) . "',
				'" . escapeString($catInfo) . "',
				'" . escapeString($catIcon) . "'
			)";
		WCF::getDB()->sendQuery($sql);
		rmCategoryEditor::resetCache();
	}

	/**
	 * Updates a category
	 *
	 * @param integer $catID
	 * @param string $catName
	 * @param integer $catOrder
	 * @param integer $catParent
	 * @param string $catDescription
	 * @param string $catIcon
	 */
	public static function update($catID, $catName, $catOrder, $catParent, $catDescription, $catIcon, $catInfo) {
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_categories
			SET catName='" . escapeString($catName) . "',
				catOrder='" . intval($catOrder) . "',
				catParent='" . intval($catParent) . "',
				catDescription='" . escapeString($catDescription) . "',
				catInfo='" . escapeString($catInfo) . "',
				catIcon='" . escapeString($catIcon) . "'

			WHERE catID = '" . intval($catID) . "'
			";
		WCF::getDB()->sendQuery($sql);
		rmCategoryEditor::resetCache();
	}

	/**
	 * Deletes the given categorie and all children
	 * including their entries
	 *
	 * @param integer $catID
	 */
	public static function delete($catID) {
		if ($catID == 1)
			return false;
		$cat = new rmCategory($catID);
		$ids = $cat->getChildrenIDs();
		$ids [] = $catID;
		
		foreach ($ids as $id) {
			$sql = "DELETE FROM wcf" . WCF_N . "_rmarketplace_categories WHERE catID = '" . $id . "'";
			WCF::getDB()->sendQuery($sql);
			
			$sql = "SELECT * FROM wcf" . WCF_N . "_rmarketplace_entries WHERE categoryID = '" . $id . "'";
			$query = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($query)) {
				require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntryEditor.class.php');
				$entry = new MpEntryEditor($row ['entryID']);
				$entry->delete();
			}
		}
		rmCategoryEditor::resetCache();
	}

	/**
	 * moves all entries of the given cat $from to the cat $to
	 *
	 * @param integer $from
	 * @param integer $to
	 */
	public static function moveItems($from, $to) {
		$from = new rmCategoryEditor($from);
		$to = new rmCategoryEditor($to);
		
		// reset the counter of the from category to zero
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_categories SET entries = 0 WHERE catID = " . $from->catID;
		WCF::getDB()->sendQuery($sql);
		
		// update the counter of the to category and add the moved entries
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_categories SET entries=entries+" . $from->entries . " WHERE catID = " . $to->catID;
		WCF::getDB()->sendQuery($sql);
		
		// update the categoryID of the containing entries
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_entries SET categoryID = '" . intval($to->catID) . "' WHERE categoryID = '" . intval($from->catID) . "'";
		WCF::getDB()->sendQuery($sql);
		
		// reset category cache
		rmCategoryEditor::resetCache();
	}

	/**
	 * Adds an entry to this category
	 */
	public function addEntry() {
		if (isset($this->data ['entries']))
			$this->data ['entries']++;
		else
			$this->data ['entries'] = 1;
		
		$sql = "UPDATE 	wcf" . WCF_N . "_rmarketplace_categories
			SET	entries = entries + 1
			WHERE 	catID = " . $this->catID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Removes an entry from this category
	 */
	public function removeEntry() {
		$this->data ['entries']--;
		
		$sql = "UPDATE 	wcf" . WCF_N . "_rmarketplace_categories
			SET	entries = entries - 1
			WHERE 	catID = " . $this->catID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Resets the category cache after changes.
	 */
	public static function resetCache() {
		// reset cache
		WCF::getCache()->clear(WCF_DIR . 'cache/', 'cache.rMarketplaceCategory.php', true);
		
		self::$categories = self::$categoryStructure = null;
	}
}
?>