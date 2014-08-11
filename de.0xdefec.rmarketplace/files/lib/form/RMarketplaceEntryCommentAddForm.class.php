<?php
// wcf imports
require_once (WCF_DIR . 'lib/form/CaptchaForm.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/comment/RMarketplaceEntryCommentEditor.class.php');

/**
 * Shows a form to write a comment to a rmarketplace entry
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceEntryCommentAddForm extends CaptchaForm {

	// parameters
	public $comment = '';

	public $username = '';

	// pm variables
	public $pmID = 0;

	public $pm, $newPm;

	public $notificationRecipients = array ();

	public $recipientArray = array ();

	private $languages = array ();

	/**
	 * MpEntry object
	 *
	 * @var MpEntry
	 */
	public $entry = null;

	/**
	 * Creates a new RMarketplaceEntryCommentAddForm object.
	 *
	 * @param	MpEntry	$entry
	 */
	public function __construct(MpEntry $entry) {
		$this->entry = $entry;
		parent::__construct();
	}

	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		// check entry
		if (!$this->entry->isCommentable()) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		// get parameters
		if (isset($_POST ['comment']))
			$this->comment = StringUtil::trim($_POST ['comment']);
		if (isset($_POST ['username']))
			$this->username = StringUtil::trim($_POST ['username']);
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();
		
		if (empty($this->comment)) {
			throw new UserInputException('comment');
		}
		
		if (StringUtil::length($this->comment) > WCF::getUser()->getPermission('user.rmarketplace.maxCommentLength')) {
			throw new UserInputException('comment', 'tooLong');
		}
		
		// username
		$this->validateUsername();
	}

	/**
	 * Validates the username.
	 */
	protected function validateUsername() {
		// only for guests
		if (WCF::getUser()->userID == 0) {
			// username
			if (empty($this->username)) {
				throw new UserInputException('username');
			}
			if (!UserUtil::isValidUsername($this->username)) {
				throw new UserInputException('username', 'notValid');
			}
			if (!UserUtil::isAvailableUsername($this->username)) {
				throw new UserInputException('username', 'notAvailable');
			}
			
			WCF::getSession()->setUsername($this->username);
		} else {
			$this->username = WCF::getUser()->username;
		}
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		
		$this->languages [WCF::getLanguage()->getLanguageID()] = WCF::getLanguage();
		$this->languages [0] = WCF::getLanguage();
		
		// save comment
		$comment = RMarketplaceEntryCommentEditor::create($this->entry->entryID, $this->entry->userID, $this->comment, WCF::getUser()->userID, $this->username);
		$this->saved();
		
		if (MODULE_PM)
			require_once (WCF_DIR . 'lib/data/message/pm/PMEditor.class.php');
		require_once (WCF_DIR . 'lib/data/user/UserProfile.class.php');
		
		$recipient = new UserProfile($this->entry->userID);
		
		// send pm notification to owner of the entry
		if (MODULE_PM && $this->entry->userID != WCF::getUser()->userID && $recipient->getUserOption('settings.communication.rmarketplace.pmOnComment')) {
			// only send notification if author != entryOwner
			

			// check if we can send this user a pm
			if ($recipient->getPermission('user.pm.canUsePm') && $recipient->acceptPm) {
				// check if the users wants to be notified
				if ($recipient->emailOnPm) {
					$this->notificationRecipients [$recipient->userID] = $recipient;
				}
				
				$this->recipientArray = array (
						array (
								'userID' => $recipient->userID, 
								'username' => $recipient->username 
						) 
				);
				
				// get the pm content
				if (!isset($this->languages [$recipient->languageID])) {
					$this->languages [$recipient->languageID] = new Language($recipient->languageID);
				}
				
				$pmData = array (
						'subject' => $this->languages [$recipient->languageID]->get("de.0xdefec.rmarketplace.newCommentPm.subject"), 
						'text' => $this->languages [$recipient->languageID]->get("de.0xdefec.rmarketplace.newCommentPm.text") 
				);
				
				// replace variables in the pm subject and text
				foreach ($pmData as $idx=>$str) {
					$pmData [$idx] = str_replace(array (
							'{$userID}', 
							'{$username}', 
							'{$entryID}' 
					), array (
							WCF::getUser()->userID, 
							WCF::getUser()->username, 
							$this->entry->entryID 
					), $str);
				}
				
				$this->pmText = $pmData ['text'];
				$this->pmSubject = $pmData ['subject'];
				
				$this->newPm = PMEditor::create(false, $this->recipientArray, array (), $this->pmSubject, $this->pmText, WCF::getUser()->userID, WCF::getUser()->username);
				
				// send e-mail notification
				if (count($this->notificationRecipients) > 0) {
					$this->newPm->sendNotifications($this->notificationRecipients);
				}
				
				// apply rules
				$this->newPm->applyRules();
			}
		}
		
		// forward
		HeaderUtil::redirect('index.php?page=RMarketplaceEntry&entryID=' . $this->entry->entryID . '&commentID=' . $comment->commentID . SID_ARG_2ND_NOT_ENCODED . '#comment' . $comment->commentID);
		exit();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array (
				'comment' => $this->comment, 
				'username' => $this->username, 
				'maxTextLength' => WCF::getUser()->getPermission('user.rmarketplace.maxCommentLength') 
		));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		if (!MODULE_RMARKETPLACE) {
			throw new IllegalLinkException();
		}
		parent::show();
	}
}
?>