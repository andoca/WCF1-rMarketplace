<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/comment/RMarketplaceEntryComment.class.php');

/**
 * Class to edit rMarketplaceEntryComments
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceEntryCommentEditor extends RMarketplaceEntryComment {

	/**
	 * Creates a new comment
	 *
	 * @param	integer		$entryID
	 * @param	integer		$ownerID
	 * @param	string		$comment
	 * @param	integer		$userID
	 * @param	string		$username
	 * @param	integer		$time
	 * @return	RMarketplaceEntryCommentEditor
	 */
	public static function create($entryID, $ownerID, $comment, $userID, $username, $time = TIME_NOW) {
		$sql = "INSERT INTO	wcf" . WCF_N . "_rmarketplace_comments
					(entryID, 
					ownerID, 
					userID, 
					username, 
					comment, 
					time)
			VALUES	(
					" . $entryID . ", 
					" . $ownerID . ", 
					" . $userID . ", 
					'" . escapeString($username) . "', 
					'" . escapeString($comment) . "', 
					" . $time . ")";
		WCF::getDB()->sendQuery($sql);
		
		// get id
		$commentID = WCF::getDB()->getInsertID("wcf" . WCF_N . "_rmarketplace_comment", 'commentID');
		
		// update entry
		$sql = "UPDATE	wcf" . WCF_N . "_rmarketplace_entries
			SET	comments = comments + 1
			WHERE	entryID = " . $entryID;
		WCF::getDB()->sendQuery($sql);
		
		return new RMarketplaceEntryCommentEditor($commentID);
	}

	/**
	 * Updates the comment.
	 *
	 * @param	string		$comment
	 */
	public function update($comment) {
		$sql = "UPDATE	wcf" . WCF_N . "_rmarketplace_comments
			SET	comment = '" . escapeString($comment) . "'
			WHERE	commentID = " . $this->commentID;
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Deletes this comment.
	 */
	public function delete() {
		// update entry
		$sql = "UPDATE	wcf" . WCF_N . "_rmarketplace_entries
			SET	comments = comments - 1
			WHERE	entryID = " . $this->entryID;
		WCF::getDB()->sendQuery($sql);
		
		// delete comment
		$sql = "DELETE FROM	wcf" . WCF_N . "_rmarketplace_comments
			WHERE		commentID = " . $this->commentID;
		WCF::getDB()->sendQuery($sql);
	}
}
?>