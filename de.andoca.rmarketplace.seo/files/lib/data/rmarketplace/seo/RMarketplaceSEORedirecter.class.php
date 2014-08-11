<?php
//wcf imports
require_once (WCF_DIR . 'lib/data/page/seo/SEOUtil.class.php');
//own imports
require_once (WCF_DIR . 'lib/data/rmarketplace/seo/RMarketplaceSEORewriter.class.php');

/**
 * Redirects SEO links
 * 
 * @author		BBLL
 * @copyright	2005 - 2010
 * @license		Commercial
 * @package		de.bbll.rmarketplace.seo
 * @subpackage	data.rmarketplace.seo
 * @category 	RMarketplace
 */
class RMarketplaceSEORedirecter extends RMarketplaceSEORewriter {

	public $encodeHTML = false;

	/**
	 * Redirects (HTTP 301) to SEO links.
	 * 
	 * @access	public
	 * @param	string		$requestURI
	 * @param	string		$className
	 * @return	void
	 */
	public function redirect($requestURI, $className) {
		if (SEO_ENABLE_301_REDIRECTS) {
			switch ($className) {
				case "RMarketplacePage":
					// Market index
					if (SEO_REWRITE_MARKET_INDEX) {
						// Forward index page
						if (preg_match("~^index\.php\?page=RMarketplace&cat=0$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketIndexURLs((isset($match [1]) ? $match [1] : "")));
						}
						// Forward sorted index page
						if (preg_match("~^index\.php\?page=RMarketplace&cat=0&type=(search|offer)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketIndexSortedURLs($match [1], (isset($match [2]) ? $match [2] : "")));
						}
						// Forward sorted index page
						if (preg_match("~^index\.php\?page=RMarketplace&type=(search|offer)&cat=0$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketIndexSortedURLs($match [1], (isset($match [2]) ? $match [2] : "")));
						}
						// Forward multiple index page
						if (preg_match("~^index\.php\?page=RMarketplace&cat=0&pageNum=(\d+)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketIndexMultipleURLs($match [1], (isset($match [2]) ? $match [2] : "")));
						}
						// Forward multiple and sorted index page
						if (preg_match("~^index\.php\?page=RMarketplace&cat=0&type=(search|offer)&pageNum=(\d+)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketIndexMultipleSortedURLs($match [2], $match [1], (isset($match [3]) ? $match [3] : "")));
						}
						// Forward index page
						if (preg_match("~^index\.php\?page=RMarketplace&type=&cat=0~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketIndexURLs((isset($match [1]) ? $match [1] : "")));
						}
						// Forward index page
						if (preg_match("~^index\.php\?page=RMarketplace$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketIndexURLs((isset($match [1]) ? $match [1] : "")));
						}
					}
					// Market pages
					if (SEO_REWRITE_MARKET_PAGES) {
						// Forward page
						if (preg_match("~^index\.php\?page=RMarketplace&cat=(\d+)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketPagesURLs($match [1], (isset($match [2]) ? $match [2] : "")));
						}
						// Forward sorted page
						if (preg_match("~^index\.php\?page=RMarketplace&type=(search|offer)&cat=(\d+)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketPagesSortedURLs($match [2], $match [1], (isset($match [3]) ? $match [3] : "")));
						}
						// Forward sorted page
						if (preg_match("~^index\.php\?page=RMarketplace&cat=(\d+)&type=(search|offer)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketPagesSortedURLs($match [1], $match [2], (isset($match [3]) ? $match [3] : "")));
						}
						// Forward multiple page
						if (preg_match("~^index\.php\?page=RMarketplace&cat=(\d+)&pageNum=(\d+)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketPagesMultipleURLs($match [1], $match [2], (isset($match [3]) ? $match [3] : "")));
						}
						// Forward multiple sorted page
						if (preg_match("~^index\.php\?page=RMarketplace&cat=(\d+)&type=(search|offer)&pageNum=(\d+)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketPagesMultipleSortedURLs($match [1], $match [3], $match [2], (isset($match [4]) ? $match [4] : "")));
						}
						// Forward page
						if (preg_match("~^index\.php\?page=RMarketplace&type=&cat=(\d+)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketPagesURLs($match [1], (isset($match [2]) ? $match [2] : "")));
						}
					}
				break;
				case "RMarketplaceEntryPage":
					// Market entries
					if (SEO_REWRITE_MARKET_ITEMS) {
						// Forward entry pages
						if (preg_match("~^index\.php\?page=RMarketplaceEntry&entryID=(\d+)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketItemURLs($match [1], (isset($match [2]) ? $match [2] : "")));
						}
					}
				break;
				case "RMarketplaceMapPage":
					// Market map
					if (SEO_REWRITE_MARKET_MAP) {
						// Forward main map
						if (preg_match("~^index\.php\?page=RMarketplaceMap&cat=0$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketMapOverviewURLs((isset($match [1]) ? $match [1] : "")));
						}
						// Forward categorized map
						if (preg_match("~^index\.php\?page=RMarketplaceMap&cat=(\d+)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketMapURLs($match [1], (isset($match [2]) ? $match [2] : "")));
						}
						// Forward filter categorized map
						if (preg_match("~^index\.php\?page=RMarketplaceMap&cat=(\d+)&type=(offer|search)$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketMapTypeURLs($match [1], $match [2], (isset($match [3]) ? $match [3] : "")));
						}
					}
				break;
				case "RMarketplaceWidget":
					// Market widget
					if (SEO_REWRITE_MARKET_WIDGET and MP_EXTERNAL_ENABLED) {
						// Forward widget page
						if (preg_match("~^index\.php\?page=RMarketplaceWidget$~", $requestURI, $match)) {
							// Forward
							$this->forward($this->parseMarketWidgetURLs((isset($match [1]) ? $match [1] : "")));
						}
					}
				break;
			}
		}
	}

	/**
	 * Does the redirect (HTTP 301).
	 * 
	 * @access	protected
	 *
	 * @param	string		$url
	 * @param	boolean		$sendHeader
	 * 
	 * @return	void
	 */
	protected function forward($url, $sendHeader = true) {
		//send 301 header
		if ($sendHeader) {
			header('HTTP/1.0 301 Moved Permanently');
		}
		//redirect
		HeaderUtil::redirect($url, false);
		exit();
	}

	/**
	 * @see	SEORewriter::appendQueryString()
	 */
	public function appendQueryString($link, $queryString) {
		return SEORewriter::appendQueryString($link, $queryString);
	}
}
?>