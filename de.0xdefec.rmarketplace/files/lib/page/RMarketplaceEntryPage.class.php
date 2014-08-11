<?php
// wcf imports
require_once (WCF_DIR . 'lib/page/AbstractPage.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/comment/RMarketplaceEntryCommentList.class.php');
require_once (WCF_DIR . 'lib/page/util/menu/PageMenu.class.php');
require_once (WCF_DIR . 'lib/page/MultipleLinkPage.class.php');
require_once (WCF_DIR . 'lib/data/message/sidebar/MessageSidebarFactory.class.php');

/**
 * page to display one entry
 *
 * @author Andreas Diendorfer
 * @copyright Andreas Diendorfer
 * @license Proprietary license - see http://www.selbstzweck.net
 * @package de.0xdefec.rMarketplace
 */
class RMarketplaceEntryPage extends MultipleLinkPage {

	public $templateName = 'rmarketplaceEntry';

	/**
	 * list of tags.
	 *
	 * @var array<Tag>
	 */
	public $tags = array ();

	/**
	 * sidebar factory object
	 *
	 * @var MessageSidebarFactory
	 */
	public $sidebarFactory = null;

	public $action;

	/**
	 * comment object
	 *
	 * @var RMarketplaceEntryComment
	 */
	public $comment = null;

	/**
	 * comment id
	 *
	 * @var integer
	 */
	public $commentID = 0;

	/**
	 *
	 * @see Page::readParameters();
	 */
	public function readParameters() {
		parent::readParameters();
		
		// don't check the permission here - it will be checked in
		// Entry::userCanView() down in self::readData()
		// WCF::getUser()->checkPermission('user.rmarketplace.canView');
		
		// get entry id
		if (isset($_GET ['entryID']))
			$this->entryID = intval($_GET ['entryID']);
		else
			throw new IllegalLinkException();
		
		if (isset($_REQUEST ['action']))
			$this->action = $_REQUEST ['action'];
		if (isset($_REQUEST ['commentID']))
			$this->commentID = intval($_REQUEST ['commentID']);
		if ($this->commentID != 0) {
			$this->comment = new RMarketplaceEntryComment($this->commentID);
			if (!$this->comment->commentID || $this->comment->entryID != $this->entryID) {
				throw new IllegalLinkException();
			}
			
			// check permissions
			if ($this->action == 'edit' && !$this->comment->isEditable()) {
				throw new PermissionDeniedException();
			}
						
			// get page number
			$sql = "SELECT	COUNT(*) AS comments
				FROM 	wcf".WCF_N."_rmarketplace_comments
				WHERE 	entryID = ".$this->entryID."
					AND time >= ".$this->comment->time;
			$result = WCF::getDB()->getFirstRow($sql);
			$this->pageNo = intval(ceil($result['comments'] / $this->itemsPerPage));
		}
		// init comment list
		$this->commentList = new RMarketplaceEntryCommentList();
		$this->commentList->sqlConditions .= 'rmarketplace_comments.entryID = ' . $this->entryID;
		$this->commentList->sqlOrderBy = 'rmarketplace_comments.time DESC';
	
	}

	/**
	 *
	 * @see Page::readData();
	 */
	public function readData() {
		parent::readData();
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.de.0xdefec.rmarketplace.header.menu');
		
		$this->entry = new MpEntry($this->entryID);
		if (!$this->entry->entryID || !$this->entry->userCanView())
			throw new IllegalLinkException();
		$this->entry->clickCount();
		
		// comments
		// read objects
		$this->commentList->sqlOffset = ($this->pageNo - 1) * $this->itemsPerPage;
		$this->commentList->sqlLimit = $this->itemsPerPage;
		$this->commentList->readObjects();
		
		// get tags
		if (defined('MODULE_TAGGING') && MODULE_TAGGING && RM_ENABLE_TAGS) {
			$this->readTags();
		}
		
		// remove embedded attachments
		$this->entry->removeEmbeddedAttachments();
		
		// init sidebars
		$this->sidebarFactory = new MessageSidebarFactory($this);
		$this->sidebarFactory->create($this->entry);
		$this->sidebarFactory->init();
	
	}

	/**
	 *
	 * @see Page::assignVariables();
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// init form
		if ($this->entry->isCommentable()) {
			if ($this->action == 'edit') {
				require_once (WCF_DIR . 'lib/form/RMarketplaceEntryCommentEditForm.class.php');
				new RMarketplaceEntryCommentEditForm($this->comment);
			} else {
				require_once (WCF_DIR . 'lib/form/RMarketplaceEntryCommentAddForm.class.php');
				new RMarketplaceEntryCommentAddForm($this->entry);
			}
		}
		
		WCF::getTPL()->assign(array (
				'entry' => $this->entry, 
				'tags' => $this->tags, 
				'comments' => $this->commentList->getObjects(), 
				'commentID' => $this->commentID, 
				'sidebarFactory' => $this->sidebarFactory, 
				'attachments' => $this->entry->attachments, 
				'allowSpidersToIndexThisPage' => MP_SPIDER_INDEX 
		));
	}

	public function show() {
		if (!MODULE_RMARKETPLACE) {
			throw new IllegalLinkException();
		}
		
		parent::show();
	}

	/**
	 *
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		parent::countItems();
		
		return $this->commentList->countObjects();
	}

	/**
	 * Reads the tags of this entry
	 */
	protected function readTags() {
		$this->tags = $this->entry->getTags(WCF::getSession()->getVisibleLanguageIDArray());
	}

}
?>