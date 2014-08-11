<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/DatabaseObject.class.php');

/**
 * handles the available categories
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class rmCategory extends DatabaseObject {
	public $categorySelect = null;

	protected $totalEntryCount = null;
	protected $parentCategories = null;

	protected static $categories = null;
	protected static $categoryStructure = null;

	/**
	 * loads the category
	 *
	 * @param integer $categoryID
	 * @param array $row
	 */
	public function __construct($categoryID, $row = null, $cacheObject = null) {
		if ($categoryID !== null) $cacheObject = self::getCategory($categoryID);
		if ($row != null) parent::__construct($row);
		if ($cacheObject != null) parent::__construct($cacheObject->data);
	}

	/**
	 * Gets the category with the given category id from cache.
	 *
	 * @param 	integer		$categoryID	id of the requested category
	 * @return	Category
	 */
	public static function getCategory($categoryID) {
		self::loadCache();

		if (!isset(self::$categories[$categoryID])) {
			throw new IllegalLinkException();
		}

		return self::$categories[$categoryID];
	}

	/**
	 * gets the amount of entries in a category
	 *
	 * @param integer $categoryID
	 * @return integer
	 */
	public function getItemsCount() {
		if ($this->totalEntryCount === null) {
			$this->totalEntryCount = $this->entries;
			$children = $this->getChildren();
			foreach ($children as $child) {
				$this->totalEntryCount += $child->entries;
			}
		}

		return $this->totalEntryCount;
	}

	/**
	 * Get all parentelements (father, grandfather, ...) of a category
	 *
	 * @return array
	 */
	public function getParents() {
		if ($this->parentCategories === null) {
			$this->parentCategories = array();
			self::loadCache();

			$parentCategory = $this;
			while ($parentCategory->catParent != 0) {
				$parentCategory = self::$categories[$parentCategory->catParent];
				array_unshift($this->parentCategories, $parentCategory);
			}
		}

		return $this->parentCategories;
	}

	/**
	 * gets all child categories of the given one
	 *
	 * @param integer $catID
	 * @return array
	 */
	public function getChildren($catID = null) {
		if ($catID === null) $catID = $this->catID;
		$catIDArray = (is_array($catID) ? $catID : array($catID));
		$subCategoryArray = array();

		// load cache
		self::loadCache();
		foreach ($catIDArray as $catID) {
			$subCategoryArray = array_merge($subCategoryArray, self::makeSubCategoryArray($catID));
		}

		return $subCategoryArray;
	}

	/**
	 * get only the IDs of children caths
	 *
	 * @return array
	 */
	public function getChildrenIDs() {
		$subCategoryIDArray = array();

		// load cache
		self::loadCache();
		$subCategoryIDArray = self::makeSubCategoryIDArray($this->catID);

		return $subCategoryIDArray;
	}

	/**
	 * Returns a list of subcategories.
	 *
	 * @param	integer		$parentCategoryID
	 * @return	array<integer>
	 */
	public static function makeSubCategoryIDArray($parentCategoryID) {
		if (!isset(self::$categoryStructure[$parentCategoryID])) {
			return array();
		}

		$subCategoryIDArray = array();
		foreach (self::$categoryStructure[$parentCategoryID] as $categoryID) {
			$subCategoryIDArray = array_merge($subCategoryIDArray, self::makeSubCategoryIDArray($categoryID));
			$subCategoryIDArray[] = $categoryID;
		}

		return $subCategoryIDArray;
	}

	/**
	 * Returns a list of subcategories.
	 *
	 * @param	integer		$parentCategoryID
	 * @return	array<Category>
	 */
	public static function makeSubCategoryArray($parentCategoryID) {
		if (!isset(self::$categoryStructure[$parentCategoryID])) {
			return array();
		}

		$subCategoryArray = array();
		foreach (self::$categoryStructure[$parentCategoryID] as $categoryID) {
			$subCategoryArray = array_merge($subCategoryArray, self::makeSubCategoryArray($categoryID));
			$subCategoryArray[] = new rmCategory($categoryID);
		}

		return $subCategoryArray;
	}

	/**
	 * gets the amount of children categories
	 *
	 * @return integer
	 */
	public function getChildrenCount() {
		if (isset($this->childrenCount)) return $this->childrenCount;
		$this->childrenCount = count($this->getChildren());
		return $this->childrenCount;
	}

	/**
	 * Loads the category cache
	 */
	protected static function loadCache() {
		if (self::$categories === null || self::$categoryStructure) {
			WCF::getCache()->addResource('rMarketplaceCategory', WCF_DIR.'cache/cache.rMarketplaceCategory.php', WCF_DIR.'lib/system/cache/CacheBuilderRMarketplaceCategory.class.php');
			self::$categories = WCF::getCache()->get('rMarketplaceCategory', 'categories');
			self::$categoryStructure = WCF::getCache()->get('rMarketplaceCategory', 'categoryStructure');
		}
	}

	/* old Ctaegories class functions */
	/**
	 * getCategories function.
	 *
	 * @param integer $parent
	 * @return array
	 */
	public function getCategories($parent = null) {
		self::loadCache();
		if ($parent === null) {
			self::sort(self::$categories, 'catOrder');
			return self::$categories;
		}
		else {
			$categories = array();
			if(isset(self::$categoryStructure[$parent]) && is_array(self::$categoryStructure[$parent])) {
				foreach (self::$categoryStructure[$parent] as $categoryID) {
					$categories[] = new rmCategory($categoryID);
				}
			}
			return $categories;
		}
	}

	/**
	 * checks if a given catID exists
	 *
	 * @param integer $catID
	 * @return boolean
	 */
	public function exists($catID) {
		self::loadCache();
		return (isset(self::$categories[$catID]));
	}

	/**
	 * Gets the whole tree of categories as array
	 *
	 * @return array
	 */
	public function getCategoryTree() {
		self::loadCache();
		return self::$categoryStructure;
	}

	/**
	 * Gets the categorytree to be displayed in a select form
	 *
	 * @return array
	 */
	public function getCategoriesSelect() {
		self::loadCache();
		if (!$this->categorySelect) {
			$this->categorySelect = array();
			$this->makeCategoriesSelect(0, 0);
		}

		return $this->categorySelect;
	}

	/**
	 * Helper function to recursivly parse the category tree
	 *
	 * @param integer $parentID
	 * @param integer $level
	 */
	protected function makeCategoriesSelect($parentID, $level) {
		if (! isset(self::$categoryStructure[$parentID])) return;

		foreach ( self::$categoryStructure[$parentID] as $catID ) {
			$cat = new rmCategory($catID);

			// we must encode html here because the htmloptions plugin doesn't do it
			$name = WCF::getLanguage()->get(StringUtil::encodeHTML($cat->catName));
			if ($level > 0) $name = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $level) . ' ' . WCF::getLanguage()->get($name);

			$this->categorySelect[$catID] = $name;
			$this->makeCategoriesSelect($catID, $level + 1);
		}
	}
}
?>