<?php
// wcf imports
require_once(WCF_DIR.'lib/data/DatabaseObject.class.php');

/**
 * Represents a rMarketplace entry comment.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceEntryComment extends DatabaseObject {
	/**
	 * Creates a new RMarketplaceEntryComment object.
	 *
	 * @param	integer		$commentID
	 * @param 	array<mixed>	$row
	 */
	public function __construct($commentID, $row = null) {
		if ($commentID !== null) {
			$sql = "SELECT	*
				FROM 	wcf".WCF_N."_rmarketplace_comments
				WHERE 	commentID = ".$commentID;
			$row = WCF::getDB()->getFirstRow($sql);
		}
		parent::__construct($row);
	}
	
	/**
	 * Returns true, if the active user can edit this comment.
	 * 
	 * @return	boolean
	 */
	public function isEditable() {
		if (($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.rmarketplace.canEditOwnComment')) || WCF::getUser()->getPermission('mod.rmarketplace.canModerate')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns true, if the active user can delete this comment.
	 * 
	 * @return	boolean
	 */
	public function isDeletable() {
		if (($this->userID && $this->userID == WCF::getUser()->userID && WCF::getUser()->getPermission('user.rmarketplace.canDeleteOwnComment')) || WCF::getUser()->getPermission('mod.rmarketplace.canModerate')) {
			return true;
		}
		return false;
	}
	
	/**
	 * Returns an editor object for this comment.
	 *
	 * @return	RMarketplaceEntryCommentEditor
	 */
	public function getEditor() {
		require_once(WCF_DIR.'lib/data/rmarketplace/mpEntry/comment/RMarketplaceEntryCommentEditor.class.php');
		return new RMarketplaceEntryCommentEditor(null, $this->data);
	}
}
?>