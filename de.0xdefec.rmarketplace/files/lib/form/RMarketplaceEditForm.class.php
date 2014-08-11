<?php
require_once (WCF_DIR . 'lib/form/RMarketplaceAddForm.class.php');

/**
 * form to edit an entry
 *
 * @author Andreas Diendorfer
 * @copyright Andreas Diendorfer
 * @license Proprietary license - see http://www.selbstzweck.net
 * @package de.0xdefec.rMarketplace
 */
class RMarketplaceEditForm extends RMarketplaceAddForm {

	public $templateName = 'rmarketplaceEdit';

	public $entryID = null;

	public $attachments = array ();

	/**
	 *
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if (isset($_REQUEST ['entryID']))
			$this->entryID = intval($_REQUEST ['entryID']);
		
		if (!$this->entryID)
			throw new IllegalLinkException();
		$this->entry = new MpEntryEditor($this->entryID);
		if (!$this->entry->userCanEdit(WCF::getUser()->userID))
			throw new PermissionDeniedException();
	}

	/**
	 *
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
	
	}

	/**
	 *
	 * @see Form::submit()
	 */
	public function submit() {
		// call submit event
		EventHandler::fireAction($this, 'submit');
		
		$this->readFormParameters();
		
		try {
			// attachment handling
			if ($this->showAttachments) {
				$this->attachmentListEditor->handleRequest();
				
				if (count($this->attachmentListEditor->attachments) == 0) {
					// we don't have attachments - neither new nor old ones,
					// check if we should have
					if ((MP_ATTACHMENTS_SEARCH && $this->type == "search") || (MP_ATTACHMENTS_OFFER && $this->type == "offer")) {
						
						throw new UserInputException('attachmentMissing');
					}
				}
			}
			
			// preview
			if ($this->preview) {
				require_once WCF_DIR . 'lib/data/message/bbcode/AttachmentBBCode.class.php';
				AttachmentBBCode::setAttachments($this->attachmentListEditor->getSortedAttachments());
				WCF::getTPL()->assign('preview', MpEntry::createPreview($this->subject, $this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes));
			}
			// send message or save as draft
			if ($this->send) {
				$this->validate();
				// no errors
				$this->save();
			}
		} catch (UserInputException $e) {
			$this->errorField = $e->getField();
			$this->errorType = $e->gettype();
		}
	}

	/**
	 *
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
	}

	/**
	 *
	 * @see Form::save()
	 */
	public function save() {
		MessageForm::save();
		$this->entry->update($this->subject, $this->text, $this->type, $this->categoryID, $this->country, $this->zipcode, $this->price, $this->attachmentListEditor, $this->getOptions());
		
		// update tags
		if (defined('MODULE_TAGGING') && MODULE_TAGGING && RM_ENABLE_TAGS) {
			$this->entry->updateTags(TaggingUtil::splitString($this->tags));
		}
		
		$this->saved();
		
		// forward to post
		$url = 'index.php?page=RMarketplaceEntry&entryID=' . $this->entry->entryID . SID_ARG_2ND_NOT_ENCODED;
		HeaderUtil::redirect($url);
		exit();
	}

	/**
	 *
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->zipcode = $this->entry->zipcode;
			$this->text = $this->entry->text;
			$this->subject = $this->entry->subject;
			$this->type = $this->entry->type;
			$this->categoryID = $this->entry->categoryID;
			$this->country = $this->entry->country;
			$this->price = $this->entry->price;
			$this->isCommentable = $this->entry->isCommentable;
			
			// tags
			if (defined('MODULE_TAGGING') && MODULE_TAGGING && RM_ENABLE_TAGS) {
				$this->tags = TaggingUtil::buildString($this->entry->getTags(array (
						0 
				)));
			}
		}
		
		try {
			$this->attachments = $this->entry->attachments;
			if (count($this->attachments) > 0) {
				require_once (WCF_DIR . 'lib/data/attachment/MessageAttachmentList.class.php');
				MessageAttachmentList::removeEmbeddedAttachments($this->attachments);
			}
		} catch (SystemException $e) {}
	}

	/**
	 *
	 * @see MessageForm::saveOptions()
	 */
	protected function saveOptions() {
		if (WCF::getUser()->userID) {
			$options = array ();
			
			// wysiwyg
			$options ['wysiwygEditorMode'] = $this->wysiwygEditorMode;
			$options ['wysiwygEditorHeight'] = $this->wysiwygEditorHeight;
			
			// options
			if (WCF::getUser()->getPermission('user.' . $this->permissionType . '.canUseBBCodes')) {
				$options [$this->permissionType . 'ParseURL'] = $this->parseURL;
			}
			
			$editor = WCF::getUser()->getEditor();
			$editor->updateOptions($options);
		}
	}

	protected function doFloodControl() {
		// this is not nice.. overrides the floodControl because we don't need
		// it for editing
		return;
	}

	/**
	 *
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		WCF::getTPL()->assign(array (
				
				'entryID' => $this->entryID, 
				'form' => $this, 
				'entry' => $this->entry, 
				'entryAttachments' => $this->attachments 
		));
	}

	/**
	 *
	 * @see Page::show()
	 */
	public function show() {
		if (!MODULE_RMARKETPLACE) {
			throw new IllegalLinkException();
		}
		
		$this->attachmentListEditor = new MessageAttachmentListEditor(array (
				$this->entryID 
		), 'rmentry', PACKAGE_ID, WCF::getUser()->getPermission('user.rmarketplace.maxAttachmentSize'), WCF::getUser()->getPermission('user.rmarketplace.allowedAttachmentExtensions'), WCF::getUser()->getPermission('user.rmarketplace.maxAttachmentCount'), $thumbnailWidth = RM_ATTACHMENT_THUMBNAIL_WIDTH, $thumbnailHeight = RM_ATTACHMENT_THUMBNAIL_HEIGHT, $addSourceInfo = RM_ATTACHMENT_THUMBNAIL_ADD_SOURCE_INFO);
		
		// show form
		parent::show();
	}
}
?>