<?php
// wcf imports
require_once(WCF_DIR.'lib/action/AbstractSecureAction.class.php');
require_once(WCF_DIR.'lib/data/rmarketplace/mpEntry/comment/RMarketplaceEntryCommentEditor.class.php');

/**
 * Deletes a rmarketplace entry comment.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceEntryCommentDeleteAction extends AbstractSecureAction {
	/**
	 * comment id
	 *
	 * @var integer
	 */
	public $commentID = 0;

	/**
	 * comment editor object
	 *
	 * @var RMarketaceEntryCommentEditor
	 */
	public $comment = null;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['commentID'])) $this->commentID = intval($_REQUEST['commentID']);
		$this->comment = new RMarketplaceEntryCommentEditor($this->commentID);
		if (!$this->comment->commentID) {
			throw new IllegalLinkException();
		}
		if (!$this->comment->isDeletable()) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();

		// delete comment
		$this->comment->delete();
		$this->executed();

		// forward
		HeaderUtil::redirect('index.php?page=RMarketplaceEntry&entryID='.$this->comment->entryID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>