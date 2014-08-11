<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/message/Message.class.php');
require_once (WCF_DIR . 'lib/data/message/sidebar/MessageSidebarObject.class.php');
require_once (WCF_DIR . 'lib/data/user/UserProfile.class.php');
require_once (WCF_DIR . 'lib/data/message/bbcode/MessageParser.class.php');
require_once (WCF_DIR . 'lib/data/message/bbcode/AttachmentBBCode.class.php');
require_once (WCF_DIR . 'lib/data/message/util/KeywordHighlighter.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategory.class.php');

/**
 * Represents a entry in the marketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class MpEntry extends Message implements MessageSidebarObject {

	/**
	 * User that created the entry
	 *
	 * @var UserProfile
	 */
	public $user = null;

	/**
	 * Array of the IDs of all included attachments
	 *
	 * @var array
	 */
	public $attachmentEntryIDArray = array ();

	/**
	 * AttachmentsList Object
	 *
	 * @var MessageAttachmentsList
	 */
	public $attachmentList = null;

	/**
	 * included attachments
	 *
	 * @var array
	 */
	public $attachments = array ();

	/**
	 * Enter description here...
	 *
	 * @var boolean
	 */
	public $canViewAttachmentPreview = true;

	/**
	 * Text preview of the entry
	 *
	 * @var string
	 */
	public $textPreview = null;

	/**
	 * True if the entry is new for the users
	 *
	 * @var boolean
	 */
	public $isNew = null;

	/**
	 * Constructor of a new entry
	 *
	 * @param integer $entryID
	 * @param array $row
	 */
	public function __construct($entryID, $row = null) {
		if ($row == null) {
			$sql = "SELECT * FROM wcf" . WCF_N . "_rmarketplace_entries WHERE
				entryID = '" . intval($entryID) . "'";
			$row = WCF::getDB()->getFirstRow($sql);
			// if (! isset($row['entryID']) || $row['entryID'] == 0) throw new IllegalLinkException();
		}
		parent::__construct($row);
		$this->messageID = $row ['entryID'];
		
		if (!isset($row ['isPreview'])) {
			// attachments
			

			if (isset($row ['attachments']) && $row ['attachments'] != 0) {
				$this->attachmentEntryIDArray [] = $row ['entryID'];
			}
			
			$this->readAttachments();
			$this->category = new rmCategory($this->categoryID);
		}
	}

	/**
	 * @see DatabaseObject::handleData()
	 */
	protected function handleData($data) {
		// if (!$data['username']) $data['username'] = 'none';
		parent::handleData($data);
		if (!$this->userID)
			$this->userID = null;
		$this->user = new UserProfile($this->userID, $data);
	}

	/**
	 * get the owner of this entry
	 *
	 * @return UserProfile
	 */
	public function getUser() {
		if ($this->user == null)
			$this->user = new UserProfile($this->userID, null, $this->username);
		return $this->user;
	}

	/**
	 * gets the name of the entries country
	 *
	 * @return string
	 */
	public function getCountryName() {
		require_once (WCF_DIR . 'lib/data/rmarketplace/countries/Countries.class.php');
		$countries = new Countries();
		if (isset($countries->countries [$this->country]))
			return $countries->countries [$this->country];
		else
			return $this->country;
	}

	/**
	 * check if the current user or the given userID can view this entry
	 *
	 * @param integer $userID
	 * @return boolean
	 */
	public function userCanView($userID = null) {
		if ($userID == null)
			$user = WCF::getUser();
		else
			$user = new UserProfile($userID);
		
		if ($user->getPermission('mod.rmarketplace.canModerate'))
			return true;
		if ($user->userID == $this->getUser()->userID && $user->userID != 0)
			return true;
		if ($this->isDisabled == true && ($user->userID != $this->getUser()->userID || $user->userID == 0))
			return false;
		if ($user->getPermission('user.rmarketplace.canView'))
			return true;
		
		return false;
	}

	/**
	 * check if the current user or the given userID can edit this entry
	 *
	 * @param integer $userID
	 * @return boolean
	 */
	public function userCanEdit($userID = null) {
		if ($userID == null)
			$user = WCF::getUser();
		else
			$user = new UserProfile($userID);
		
		if ($user->getPermission('mod.rmarketplace.canModerate'))
			return true;
		if ($user->userID == $this->getUser()->userID && $user->getPermission('user.rmarketplace.canEditOwn'))
			return true;
		
		return false;
	}

	/**
	 * check if the current user or the given userID can activate/deactivate this entry
	 *
	 * @param integer $userID
	 * @return boolean
	 */
	public function userCanDeactivate($userID = null) {
		if ($userID == null)
			$user = WCF::getUser();
		else
			$user = new UserProfile($userID);
		
		if ($user->getPermission('mod.rmarketplace.canModerate'))
			return true;
		if ($user->userID == $this->getUser()->userID && $user->getPermission('user.rmarketplace.canDeactivateOwn'))
			return true;
		
		return false;
	}

	/**
	 * check if the current user or the given userID can disable/enable this entry
	 *
	 * @param integer $userID
	 * @return boolean
	 */
	public function userCanDisable($userID = null) {
		if ($userID == null)
			$user = WCF::getUser();
		else
			$user = new UserProfile($userID);
		
		if ($user->getPermission('mod.rmarketplace.canModerate'))
			return true;
		return false;
	}

	/**
	 * check if the current user or the given userID can delete this entry
	 *
	 * @param integer $userID
	 * @return boolean
	 */
	public function userCanDelete($userID = null) {
		if ($userID == null)
			$user = WCF::getUser();
		else
			$user = new UserProfile($userID);
		
		if ($user->getPermission('mod.rmarketplace.canModerate'))
			return true;
		if ($user->userID == $this->getUser()->userID && $user->getPermission('user.rmarketplace.canDeleteOwn'))
			return true;
		
		return false;
	}

	/**
	 * check if the current user or the given userID can push this entry
	 *
	 * @param integer $userID
	 * @return boolean
	 */
	public function userCanPush($userID = null) {
		if ($userID == null)
			$user = WCF::getUser();
		else
			$user = new UserProfile($userID);
		
		if ($user->getPermission('mod.rmarketplace.canModerate'))
			return true;
		if ($user->userID == $this->getUser()->userID && $user->getPermission('user.rmarketplace.maxPushCount') > $this->pushCount)
			return true;
		
		return false;
	}

	/**
	 * pareses the text of the entry for bbcodes, smilies, ...
	 *
	 * @return string
	 */
	public function getFormattedMessage() {
		
		// parse message
		$parser = MessageParser::getInstance();
		$parser->setOutputType('text/html');
		AttachmentBBCode::setMessageID($this->entryID);
		return $parser->parse($this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes);
	}

	/**
	 * creates the entry preview
	 *
	 * @param string $subject
	 * @param string $text
	 * @param boolean $enableSmilies
	 * @param boolean $enableHtml
	 * @param boolean $enableBBCodes
	 * @return string
	 */
	public static function createPreview($subject, $text, $enableSmilies = 1, $enableHtml = 0, $enableBBCodes = 1) {
		$row = array (
				
				'entryID' => 0, 
				'subject' => $subject, 
				'text' => $text, 
				'enableSmilies' => $enableSmilies, 
				'enableHtml' => $enableHtml, 
				'enableBBCodes' => $enableBBCodes, 
				'messagePreview' => true 
		);
		
		$entry = new MpEntry(null, $row);
		return $entry->getFormattedMessage();
	}

	/**
	 * gets the author's signature to add to the entry
	 *
	 * @return string
	 */
	public function getSignature() {
		if ($this->signature === null) {
			$this->signature = '';
			
			if ($this->showSignature && (!WCF::getUser()->userID || WCF::getUser()->showSignature) && !$this->getUser()->disableSignature) {
				if ($this->getUser()->signatureCache)
					$this->signature = $this->getUser()->signatureCache;
				else if ($this->getUser()->signature) {
					$parser = MessageParser::getInstance();
					$parser->setOutputType('text/html');
					$this->signature = $parser->parse($this->getUser()->signature, $this->getUser()->enableSignatureSmilies, $this->getUser()->enableSignatureHtml, $this->getUser()->enableSignatureBBCodes, false);
				}
			}
		}
		
		return $this->signature;
	}

	/**
	 * Reads the attachments from the current entry
	 *
	 */
	protected function readAttachments() {
		// read attachments
		if (MODULE_ATTACHMENT == 1 && count($this->attachmentEntryIDArray) > 0) {
			require_once (WCF_DIR . 'lib/data/attachment/MessageAttachmentList.class.php');
			$this->attachmentList = new MessageAttachmentList($this->attachmentEntryIDArray, 'rmentry');
			$this->attachmentList->readObjects();
			$this->attachments = $this->attachmentList->getSortedAttachments($this->canViewAttachmentPreview);
			
			// set embedded attachments
			if ($this->canViewAttachmentPreview) {
				require_once (WCF_DIR . 'lib/data/message/bbcode/AttachmentBBCode.class.php');
				AttachmentBBCode::setAttachments($this->attachments);
			}
		}
	}

	public function removeEmbeddedAttachments() {
		// remove embedded attachments from list
		// removed this code from the readAttachments function to make sure, all attachments can be read
		// this methode is called when showing the entire entry
		if (MODULE_ATTACHMENT == 1 && count($this->attachmentEntryIDArray) > 0) {
			if (count($this->attachments) > 0) {
				MessageAttachmentList::removeEmbeddedAttachments($this->attachments);
			}
		}
	}

	/**
	 * gets a short text preview of the entry to display in the list
	 *
	 * @return string
	 */
	public function getTextPreview() {
		if ($this->textPreview == null) {
			require_once (WCF_DIR . 'lib/data/message/bbcode/MessageParser.class.php');
			$parser = MessageParser::getInstance();
			$parser->setOutputType('text/plain');
			$message = StringUtil::stripHTML($this->text);
			$parsedMessage = $parser->parse($message, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes, false);
			
			if (StringUtil::length($parsedMessage) > 80) {
				$parsedMessage = StringUtil::substring($parsedMessage, 0, 80) . '...';
			}
			$this->textPreview = str_replace(array (
					"\r\n", 
					"\n", 
					"\r" 
			), "", $parsedMessage);
		}
		return $this->textPreview;
	}

	/**
	 * adds +1 to the click counter if the entry is opened
	 * only count once per session and don't count the author
	 *
	 */
	public function clickCount() {
		if (WCF::getUser()->userID == $this->userID)
			return false;
		
		$clicked = (array) WCF::getSession()->getVar('rmarketplace_clicks');
		if (is_array($clicked)) {
			if (in_array($this->entryID, $clicked))
				return false;
		}
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_entries SET clicks = clicks+1
				 WHERE
				entryID = '" . intval($this->entryID) . "'";
		WCF::getDB()->sendQuery($sql);
		$clicked [] = $this->entryID;
		WCF::getSession()->register('rmarketplace_clicks', $clicked);
		return true;
	}

	/**
	 * Gets the info if a entry should be marked as old
	 *
	 * @return boolean
	 */
	public function isOld() {
		if (MP_ENTRY_OLD_TIME == 0)
			return false;
		$maxTime = TIME_NOW - (MP_ENTRY_OLD_TIME * 60 * 60 * 24);
		
		if ($this->time < $maxTime)
			return true;
		return false;
	}

	/**
	 * @see MessageSidebarObject::getMessageID()
	 */
	public function getMessageID() {
		return $this->entryID;
	}

	/**
	 * @see MessageSidebarObject::getMessageType()
	 */
	public function getMessageType() {
		return 'mpentry';
	}

	/**
	 * Returns the tags of this entry
	 *
	 * @return	array
	 */
	public function getTags($languageIDArray) {
		// include files
		require_once (WCF_DIR . 'lib/data/tag/TagEngine.class.php');
		require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/TaggedMpEntry.class.php');
		
		// get tags
		return TagEngine::getInstance()->getTagsByTaggedObject(new TaggedMpEntry(null, array (
				'entryID' => $this->entryID, 
				'taggable' => TagEngine::getInstance()->getTaggable('de.0xdefec.rmarketplace.mpentry') 
		)), $languageIDArray);
	}

	public function isGeolocated() {
		if ($this->lat && $this->lng)
			return true;
		return false;
	}

	public function isCommentable() {
		if (WCF::getUser()->getPermission('user.rmarketplace.canComment') && $this->isCommentable)
			return true;
		return false;
	}

	public function isNew() {
		if ($this->isNew !== null)
			return $this->isNew;
			
			// check if we can get the lastVisitTime from the session
		$lastVisitTime = WCF::getSession()->getVar('rmLastVisitTime');
		
		if (!$lastVisitTime) {
			if (WCF::getUser()->userID) {
				// no data saved in session and the user is logged in
				// --> try to get the time from DB
				

				$sql = "SELECT lastVisitTime FROM wcf" . WCF_N . "_rmarketplace_visit
					WHERE userID = " . WCF::getUser()->userID;
				$row = WCF::getDB()->getFirstRow($sql);
				$lastVisitTime = $row ['lastVisitTime'];
			} else
				$lastVisitTime = TIME_NOW - (60 * 60 * 24 * 3);
				
				// save lastVisitTime to session
			WCF::getSession()->register('rmLastVisitTime', $lastVisitTime);
		}
		if ($lastVisitTime < $this->time)
			return true;
		return false;
	}
}
?>