<?php
// wcf imports
require_once(WCF_DIR.'lib/form/RMarketplaceEntryCommentAddForm.class.php');

/**
 * Shows the form for editing rmarketplace entry comments.
 * 
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceEntryCommentEditForm extends RMarketplaceEntryCommentAddForm {
	/**
	 * comment editor
	 *
	 * @var RMarketplaceEntryCommentEditor
	 */
	public $commentObj = null;
	
	/**
	 * Creates a new RMarketplaceEntryCommentEditForm object.
	 *
	 * @param	RMarketplaceEntryComment		$comment
	 */
	public function __construct(RMarketplaceEntryComment $comment) {
		$this->commentObj = $comment->getEditor();
		CaptchaForm::__construct();
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		CaptchaForm::readParameters();
		
		// get comment
		if (!$this->commentObj->isEditable()) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		CaptchaForm::save();
		
		// save comment
		$this->commentObj->update($this->comment);
		$this->saved();
		
		// forward
		HeaderUtil::redirect('index.php?page=RMarketplaceEntry&entryID='.$this->commentObj->entryID.'&commentID='.$this->commentObj->commentID.SID_ARG_2ND_NOT_ENCODED.'#comment'.$this->commentObj->commentID);
		exit;
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->comment = $this->commentObj->comment;
		}
	}
}
?>