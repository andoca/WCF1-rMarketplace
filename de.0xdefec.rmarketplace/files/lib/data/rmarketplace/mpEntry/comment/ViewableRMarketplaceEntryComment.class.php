<?php
// wcf imports
require_once(WCF_DIR.'lib/data/rmarketplace/mpEntry/comment/RMarketplaceEntryComment.class.php');
require_once(WCF_DIR.'lib/data/user/UserProfile.class.php');
require_once(WCF_DIR.'lib/data/message/bbcode/SimpleMessageParser.class.php');

/**
 * Represents a viewable rmarketplaceEntry comment.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class ViewableRMarketplaceEntryComment extends RMarketplaceEntryComment {
	/**
	 * user object
	 *
	 * @var UserProfile
	 */
	protected $user = null;
	
	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);
		$this->user = new UserProfile(null, $data);
	}
	
	/**
	 * Returns the formatted comment.
	 * 
	 * @return	string
	 */
	public function getFormattedComment() {
		return SimpleMessageParser::getInstance()->parse($this->comment);
	}
	
	/**
	 * Returns the user object.
	 * 
	 * @return	UserProfile
	 */
	public function getUser() {
		return $this->user;
	}
}
?>