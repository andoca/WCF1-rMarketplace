<?php
//wcf imports
require_once (WCF_DIR . "lib/data/page/seo/SEORewriter.class.php");
require_once (WCF_DIR . "lib/data/page/seo/SEOUtil.class.php");
//own imports
require_once (WCF_DIR . "lib/data/rmarketplace/rmcategory/rmCategory.class.php");
require_once (WCF_DIR . "lib/data/rmarketplace/mpEntry/MpEntry.class.php");

/**
 * Rewrites SEO links in HTML code
 * 
 * @author		BBLL
 * @copyright	2005 - 2010
 * @license		Commercial
 * @package		de.bbll.rmarketplace.seo
 * @subpackage	data.rmarketplace.seo
 * @category 	RMarketplace
 */
class RMarketplaceSEORewriter extends SEORewriter {

	// Cache variables
	protected $cachedCategories = array ();

	protected $cachedParentCategories = array ();

	protected $cachedItemCategories = array ();

	protected $cachedItemNames = array ();

	protected $cachedMarketTitle = "";

	/**
	 * Rewrites SEO links in HTML code.
	 * 
	 * @access	public
	 * @param	string		$text
	 * @return	string
	 */
	public function rewrite($text) {
		// Fix double SEO-Plugin
		$this->pageURL = self::getPageURL();
		$text = preg_replace('~(?<=a href=")([^"]+)?(?=")~e', '$this->fixURL("$1")', $text);
		// Rewrite index if active
		if (SEO_REWRITE_MARKET_INDEX) {
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplace&amp;cat=0&amp;type=(search|offer)&amp;pageNum=(\d+)(?=")~e', '$this->parseMarketIndexMultipleSortedURLs("$2", "$1", "$3")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplace&amp;cat=0&amp;type=(search|offer)(?=")~e', '$this->parseMarketIndexSortedURLs("$1", "$2")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplace&amp;cat=0(&amp;type=|)&amp;pageNum=(\d+)(?=")~e', '$this->parseMarketIndexMultipleURLs("$2", "$3")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplace&amp;newItems=1(?=")~e', '$this->parseMarketIndexNewItemsURLs("$1")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplace&amp;cat=0(&amp;type=)?(?=")~e', '$this->parseMarketIndexURLs("$1")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplace(&amp;type=)?(?=")~e', '$this->parseMarketIndexURLs("$1")', $text);
		}
		// Rewrite offers and searches if active
		if (SEO_REWRITE_MARKET_PAGES) {
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplace&amp;cat=(\d+)&amp;type=(search|offer)&amp;pageNum=(\d+)(?=")~e', '$this->parseMarketPagesMultipleSortedURLs("$1", "$3", "$2", "$4")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplace&amp;cat=(\d+)&amp;type=(search|offer)(?=")~e', '$this->parseMarketPagesSortedURLs("$1", "$2", "$3")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplace&amp;cat=(\d+)(&amp;type=)?(?=")~e', '$this->parseMarketPagesURLs("$1", "$3")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplace&amp;cat=(\d+)&amp;pageNum=(\d+)(?=")~e', '$this->parseMarketPagesMultipleURLs("$1", "$2")', $text);
		}
		// Rewrite items if active
		if (SEO_REWRITE_MARKET_ITEMS) {
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplaceEntry&amp;entryID=(\d+)(?=")~e', '$this->parseMarketItemURLs("$1", "$2")', $text);
			$text = preg_replace('~(?<=value=")index\.php\?page=RMarketplaceEntry&amp;entryID=(\d+)~e', '$this->parseMarketItemURLs("$1", "")', $text);
		}
		// Rewrite map if active
		if (SEO_REWRITE_MARKET_MAP) {
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplaceMap&amp;cat=0(?=")~e', '$this->parseMarketMapOverviewURLs("$1")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplaceMap&amp;cat=(\d+)&amp;type=(offer|search)(?=")~e', '$this->parseMarketMapTypeURLs("$1", "$2", "$3")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplaceMap&amp;cat=(\d+)(?=")~e', '$this->parseMarketMapURLs("$1", "$2")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplaceMap(?=")~e', '$this->parseMarketMapOverviewURLs("$1")', $text);
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplaceMap~e', '$this->parseMarketMapOverviewURLs("")', $text);
		}
		// Rewrite widget if active and installed
		if (SEO_REWRITE_MARKET_WIDGET and defined("MP_EXTERNAL_ENABLED")) {
			$text = preg_replace('~(?<=href=")index\.php\?page=RMarketplaceWidget(?=")~e', '$this->parseMarketWidgetURLs("$1")', $text);
		}
		// @see SEORewriter::rewrite()
		$text = parent::rewrite($text);
		// Replace base-element
		$text = preg_replace('~<base href="' . $this->pageURL . '" />~', '', $text);
		$text = preg_replace('~<head>~', "<head>\n" . '<base href="' . $this->pageURL . '" />', $text, 1);
		// Return
		return $text;
	}

	/**
	 * Parses the normal index urls
	 * 
	 * @acces	public
	 * @param	string		$queryString
	 * @param	string		$string
	 * @return	string
	 */
	public function parseMarketIndexURLs($queryString, $string = SEO_REWRITE_MARKET_INDEX_FORMAT) {
		// Cache market title
		$this->cacheMarketTitle();
		// Parse page title
		$string = str_replace("{RMARKETPLACE_TITLE}", $this->cachedMarketTitle, $string);
		// Append query string
		$string = $this->appendQueryString($string, $queryString);
		// Return
		return $string;
	}

	/**
	 * Parses the links for new items
	 * 
	 * @access	public
	 * @param	string		$queryString
	 * @param	string		$string
	 * @return	string
	 */
	public function parseMarketIndexNewItemsURLs($queryString, $string = SEO_REWRITE_MARKET_NEW_ITEMS_INDEX_FORMAT) {
		// Cache market title
		$this->cacheMarketTitle();
		// Parse page title
		$string = str_replace("{RMARKETPLACE_TITLE}", $this->cachedMarketTitle, $string);
		// Append query string
		$string = $this->appendQueryString($string, $queryString);
		// Return
		return $string;
	}

	/**
	 * Parses the sorted index urls
	 * 
	 * @access	public
	 * @param	string		$type
	 * @param	string		$queryString
	 * @param	string		$string
	 * @return	string
	 */
	public function parseMarketIndexSortedURLs($type, $queryString, $string = SEO_REWRITE_MARKET_SORTED_INDEX_FORMAT) {
		// Parse type
		$string = str_replace("{TYPE}", $type, $string);
		// Return
		return $this->parseMarketIndexURLs($queryString, $string);
	}

	/**
	 * Parses the multiple index urls
	 * 
	 * @access	public
	 * @param	integer		$pageNo
	 * @param	string		$queryString
	 * @param	string		$string
	 * @return	string
	 */
	public function parseMarketIndexMultipleURLs($pageNo, $queryString, $string = SEO_REWRITE_MARKET_MULTIPLE_INDEX_FORMAT) {
		// Parse page no
		$string = str_replace("{PAGE_NO}", $pageNo, $string);
		// Return
		return $this->parseMarketIndexURLs($queryString, $string);
	}

	/**
	 * Parses multiple sorted index urls
	 * 
	 * @access	public
	 * @param	integer		$pageNo
	 * @parma	string		$type
	 * @param	string		$queryString
	 * @param	string		$string
	 * @return	string
	 */
	public function parseMarketIndexMultipleSortedURLs($pageNo, $type, $queryString, $string = SEO_REWRITE_MARKET_MULTIPLE_SORTED_INDEX_FORMAT) {
		// Parses page no
		$string = $this->parseMarketIndexMultipleURLs($pageNo, $queryString, $string);
		// Parses type
		$string = $this->parseMarketIndexSortedURLs($type, $queryString, $string);
		// Return
		return $string;
	}

	/**
	 * Parses the market pages urls
	 * 
	 * @access	public
	 * @param	integer		$categoryID
	 * @param	string		$queryString
	 * @param	string		$string
	 * @return	string
	 */
	public function parseMarketPagesURLs($categoryID, $queryString, $string = SEO_REWRITE_MARKET_PAGES_FORMAT) {
		// Cache market title
		$this->cacheMarketTitle();
		// Parse page title
		$string = str_replace("{RMARKETPLACE_TITLE}", $this->cachedMarketTitle, $string);
		// Parse category id
		$string = str_replace("{CATEGORY_ID}", $categoryID, $string);
		// Parse category name
		$string = str_replace("{CATEGORY_NAME}", $this->getCategoryTitle($categoryID), $string);
		// Parses the parent Categories
		$string = $this->parseMarketParentCategories($categoryID, $string);
		// Append query string
		$string = $this->appendQueryString($string, $queryString);
		// Return
		return $string;
	}

	/**
	 * Parses the multiple market pages urls
	 * 
	 * @access	public
	 * @param	integer
	 * @param	integer
	 * @param	string
	 * @param	string
	 * @return	string
	 */
	public function parseMarketPagesMultipleURLs($categoryID, $pageNo, $queryString, $string = SEO_REWRITE_MARKET_PAGES_MULTIPLE_FORMAT) {
		// Parse page no
		$string = str_replace("{PAGE_NO}", $pageNo, $string);
		// Parse as normal
		$string = $this->parseMarketPagesURLs($categoryID, $queryString, $string);
		// Return
		return $string;
	}

	/**
	 * Parses the sorted market pages urls
	 * 
	 * @access	public
	 * @param	integer		$categoryID
	 * @param	string		$type
	 * @param	string		$queryString
	 * @param	string		$string
	 * @return	string		
	 */
	public function parseMarketPagesSortedURLs($categoryID, $type, $queryString, $string = SEO_REWRITE_MARKET_PAGES_SORTED_FORMAT) {
		// Parse type
		$string = str_replace("{TYPE}", $type, $string);
		// Parse as normal
		$string = $this->parseMarketPagesURLs($categoryID, $queryString, $string);
		// Return
		return $string;
	}

	/**
	 * Parses the multiple and sorted marketplace pages urls
	 * 
	 * @access	public
	 * @param	integer		$categoryID
	 * @param	integer		$pageNo
	 * @param	string		$type
	 * @param	string		$queryString
	 * @param	string		$string
	 * @return	string	
	 */
	public function parseMarketPagesMultipleSortedURLs($categoryID, $pageNo, $type, $queryString, $string = SEO_REWRITE_MARKET_PAGES_MULTIPLE_SORTED_FORMAT) {
		// Parse page no
		$string = $this->parseMarketPagesMultipleURLs($categoryID, $pageNo, $queryString, $string);
		// Parse type
		$string = $this->parseMarketPagesSortedURLs($categoryID, $type, $queryString, $string);
		// Return
		return $string;
	}

	/**
	 * Parses marketplace item urls
	 * 
	 * @access	public
	 * @param	integer		$itemID
	 * @param	string		$queryString
	 * @param	string		$string
	 * @return	string
	 */
	public function parseMarketItemURLs($itemID, $queryString, $string = SEO_REWRITE_MARKET_ITEM_FORMAT) {
		// Cache market title
		$this->cacheMarketTitle();
		// Parse page title
		$string = str_replace("{RMARKETPLACE_TITLE}", $this->cachedMarketTitle, $string);
		// Parse item id
		$string = str_replace("{ITEM_ID}", $itemID, $string);
		// Parse item name
		if (substr_count($string, "{ITEM_NAME}"))
			$string = str_replace("{ITEM_NAME}", $this->getItemName($itemID), $string);
				// Parse category
		$string = $this->parseMarketPagesURLs($this->getItemCategoryID($itemID), $queryString, $string);
		// Append query string
		$string = $this->appendQueryString($string, $queryString);
		// Return
		return $string;
	}

	/**
	 * Parses the marketplace map urls
	 * 
	 * @access		public
	 * @param		integer			$categoryID
	 * @param		string			$queryString
	 * @param		string			$string
	 * @return		string
	 */
	public function parseMarketMapURLs($categoryID, $queryString, $string = SEO_REWRITE_MARKET_MAP_FORMAT) {
		// Cache market title
		$this->cacheMarketTitle();
		// Parse page title
		$string = str_replace("{RMARKETPLACE_TITLE}", $this->cachedMarketTitle, $string);
		// Parse category id
		$string = str_replace("{CATEGORY_ID}", $categoryID, $string);
		// Parse category name
		$string = str_replace("{CATEGORY_NAME}", $this->getCategoryTitle($categoryID), $string);
		// Parses the parent Categories
		$string = $this->parseMarketParentCategories($categoryID, $string);
		// Append query string
		$string = $this->appendQueryString($string, $queryString);
		// Return
		return $string;
	}

	/**
	 * Parses the marketplace index urls
	 * 
	 * @access		public
	 * @param		string			$queryString
	 * @param		string			$string
	 * @return		string
	 */
	public function parseMarketMapOverviewURLs($queryString, $string = SEO_REWRITE_MARKET_MAP_OVERVIEW_FORMAT) {
		// Cache market title
		$this->cacheMarketTitle();
		// Parse page title
		$string = str_replace("{RMARKETPLACE_TITLE}", $this->cachedMarketTitle, $string);
		// Append query string
		$string = $this->appendQueryString($string, $queryString);
		// Return
		return $string;
	}

	/**
	 * Parses the marketplace urls with filter
	 * 
	 * @access		public
	 * @param		integer			$categoryID
	 * @param		string			$type
	 * @param		string			$queryString
	 * @param		string			$string
	 * @return		string
	 */
	public function parseMarketMapTypeURLs($categoryID, $type, $queryString, $string = SEO_REWRITE_MARKET_MAP_TYPE_FORMAT) {
		// Replace type
		$string = str_replace("{TYPE}", $type, $string);
		// Parse as normal
		$string = $this->parseMarketMapURLs($categoryID, $queryString, $string);
		// Return
		return $string;
	}

	/**
	 * Parses the rmarketplace widget urls
	 * 
	 * @access	public
	 * @param	string		$queryString
	 * @param	string		$string
	 * @return	string
	 */
	public function parseMarketWidgetURLs($queryString, $string = SEO_REWRITE_MARKET_WIDGET_FORMAT) {
		// Cache market title
		$this->cacheMarketTitle();
		// Parse page title
		$string = str_replace("{RMARKETPLACE_TITLE}", $this->cachedMarketTitle, $string);
		// Append query string
		$string = $this->appendQueryString($string, $queryString);
		// Return
		return $string;
	}

	/**
	 * Parses the parent categories urls
	 * 
	 * @access	public
	 * @param	integer		$categoryID
	 * @param	string		$string
	 * @return	string
	 */
	public function parseMarketParentCategories($categoryID, $input, $string = SEO_REWRITE_MARKET_PARENT_CATEGORIES) {
		// Get categories
		$parents = $this->getParentCategories($categoryID);
		// Switch
		if (count($parents)) {
			// For each parent
			foreach ($parents as $key=>$value) {
				// Create temporay string
				$replaceString = $string;
				// Replace category id
				$replaceString = str_replace("{CATEGORY_ID}", $key, $replaceString);
				// Replace category name
				$replaceString = str_replace("{CATEGORY_NAME}", $value, $replaceString);
				// Create Parent-String if not isset
				if (!isset($parentString))
					$parentString = "";
						// Insert the parents
				$parentString = $parentString . $replaceString;
				// Unset temporay string
				unset($replaceString);
			}
			// Replace parent categories
			$input = str_replace("{PARENT_CATEGORIES}", $parentString, $input);
		} else {
			// Replace with nothing because they have no parents
			$input = str_replace("{PARENT_CATEGORIES}", "", $input);
		}
		// Return
		return $input;
	}

	/**
	 * Fixes the double SEO-Plugin bug
	 * 
	 * @access	public
	 * @param	string		$url
	 * @return	string
	 */
	public function fixURL($url) {
		$url = stripslashes($url);
		if (substr($url, 0, strlen($this->pageURL)) == $this->pageURL) {
			return substr($url, strlen($this->pageURL));
		}
		return $url;
	}

	/**
	 * Caches the market title
	 * 
	 * @access	public
	 */
	public function cacheMarketTitle() {
		// Check cache
		if (!empty($this->cachedMarketTitle))
			return;
				// Add to cache
		$this->cachedMarketTitle = SEOUtil::formatString(WCF::getLanguage()->get(RMARKETPLACE_TITLE));
		// Return
		return;
	}

	/**
	 * Caches the categories
	 * 
	 * @access	public
	 * @param	integer		$categoryID
	 * @return	string
	 */
	public function getCategoryTitle($categoryID) {
		// Check cache
		if (isset($this->cachedCategories [$categoryID]))
			return $this->cachedCategories [$categoryID];
				// Create instance
		$category = new rmCategory($categoryID);
		$categoryName = $category->__get("catName");
		// Prepare
		if (!empty($categoryName)) {
			// Format
			$categoryName = SEOUtil::formatString(WCF::getLanguage()->get($category->__get("catName")));
			// Encode if needed
			if ($this->encodeHTML)
				$categoryName = StringUtil::encodeHTML($categoryName);
					// Cache
			$this->cachedCategories [$categoryID] = $categoryName;
			// Return
			return $categoryName;
		}
		// Return
		return $categoryID;
	}

	/**
	 * Caches the item category id
	 * 
	 * @access	public
	 * @param	integer		$itemID
	 * @return	string
	 */
	public function getItemCategoryID($itemID) {
		// Check cache
		if (isset($this->cachedItemCategories [$itemID]))
			return $this->cachedItemCategories [$itemID];
				// Create instance
		$item = new mpEntry($itemID);
		// Get category
		$categoryID = $item->__get("categoryID");
		// Cache
		$this->cachedItemCategories [$itemID] = $categoryID;
		// Return
		return $categoryID;
	}

	/**
	 * Caches the parent categories
	 * 
	 * @access	public
	 * @param	integer		$categoryID
	 * @return	string
	 */
	public function getParentCategories($categoryID) {
		// Check cache
		if (isset($this->cachedParentCategories [$categoryID]))
			return $this->cachedParentCategories [$categoryID];
				// Get parents
		$category = new rmCategory($categoryID);
		$unformatedParents = $category->getParents();
		// Parent array for return
		$parents = array ();
		// Format parents
		foreach ($unformatedParents as $item) {
			if (!empty($item) and count($item)) {
				// Format name
				$itemCategoryName = SEOUtil::formatString(WCF::getLanguage()->get($item->__get("catName")));
				if ($this->encodeHTML)
					$itemCategoryName = StringUtil::encodeHTML($itemCategoryName);
						// Format ID
				$itemCategoryID = $item->__get("catID");
				// Save as parents
				if ($itemCategoryID != $categoryID)
					$parents [$itemCategoryID] = $itemCategoryName;
			}
		}
		// Cache parents
		$this->cachedParentCategories [$categoryID] = $parents;
		// Return
		return $parents;
	}

	/**
	 * Caches the item name
	 * 
	 * @access	public
	 * @param	integer		$itemID
	 * @return	string
	 */
	public function getItemName($itemID) {
		// Read cache
		if (isset($this->cachedItemNames [$itemID]))
			return $this->cachedItemNames [$itemID];
				// Create new instance (MpEntry)
		$item = new MpEntry($itemID);
		// Get item name and format
		$itemName = SEOUtil::formatString($item->__get("subject"));
		if ($this->encodeHTML)
			$itemName = StringUtil::encodeHTML($itemName);
				// Cache item name
		$this->cachedItemNames [$itemID] = $itemName;
		// Return
		return $itemName;
	}

	/**
	 * @see	SEORewriter::appendQueryString()
	 */
	public function appendQueryString($link, $queryString) {
		// Parents
		$link = parent::appendQueryString($link, $queryString);
		// Adds rel="nofollow"-attribut to duplicate content links
		if (!empty($queryString) and !preg_match('~^(&amp;|\?)s=[a-f0-9]{40}$~', $queryString)) {
			$link .= '" rel="nofollow';
		}
		// Return
		return $link;
	}
}
?>