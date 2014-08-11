<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntryEditor.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/countries/Countries.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategoryEditor.class.php');

require_once (WCF_DIR . 'lib/data/attachment/MessageAttachmentListEditor.class.php');
require_once (WCF_DIR . 'lib/form/MessageForm.class.php');
require_once (WCF_DIR . 'lib/system/language/Language.class.php');
require_once (WCF_DIR . 'lib/page/util/menu/PageMenu.class.php');

/**
 * Form to add an entry
 *
 * @author Andreas Diendorfer
 * @copyright Andreas Diendorfer
 * @license Proprietary license - see http://www.selbstzweck.net
 * @package de.0xdefec.rMarketplace
 */
class RMarketplaceAddForm extends MessageForm {

	public $attachmentListEditor = null;

	public $templateName = 'rmarketplaceAdd';

	public $useCaptcha = MP_USE_CAPTCHA;

	public $minCharLength = MP_MIN_CHAR_LENGTH;

	public $minWordCount = MP_MIN_WORD_COUNT;

	public $newEntry;
	
	// form parameters
	public $username = '';

	public $subject = '';

	public $type = '';

	public $categoryID = '';

	public $country = '';

	public $zipcode = '';

	public $price = '';

	public $preview, $send;

	public $tags = '';

	public $isCommentable = true;

	/**
	 *
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		WCF::getUser()->checkPermission('user.rmarketplace.canWrite');
		// we also allow guests to add entries if they have permissions
		// if (! WCF::getUser()->userID) throw new PermissionDeniedException();
		
		$this->messageTable = "wcf" . WCF_N . "_rmarketplace_entries";
		if (isset($_GET ['categoryID']))
			$this->categoryID = intval($_GET ['categoryID']);
	}

	/**
	 *
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		if (isset($_POST ['username']))
			$this->username = StringUtil::trim($_POST ['username']);
		if (isset($_POST ['preview']))
			$this->preview = (boolean) $_POST ['preview'];
		if (isset($_POST ['send']))
			$this->send = (boolean) $_POST ['send'];
		if (isset($_POST ['type']))
			$this->type = StringUtil::trim($_POST ['type']);
		if (isset($_POST ['categoryID']))
			$this->categoryID = intval($_POST ['categoryID']);
		
		if (RM_ENABLE_MAPS) {
			if (isset($_POST ['country']))
				$this->country = StringUtil::trim($_POST ['country']);
			if (isset($_POST ['zipcode']))
				$this->zipcode = StringUtil::trim($_POST ['zipcode']);
		} else {
			$this->country = null;
			$this->zipcode = null;
		}
		if (isset($_POST ['price']))
			$this->price = StringUtil::trim($_POST ['price']);
		if (isset($_POST ['tags']))
			$this->tags = StringUtil::trim($_POST ['tags']);
		if (isset($_POST ['isCommentable']))
			$this->isCommentable = intval($_POST ['isCommentable']);
		else
			$this->isCommentable = false;
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
					// we don't have attachments, check if we should have
					if ((MP_ATTACHMENTS_SEARCH && $this->type == "search") || (MP_ATTACHMENTS_OFFER && $this->type == "offer")) {
						
						throw new UserInputException('attachmentMissing');
					}
				}
			}
			
			// preview
			if ($this->preview) {
				require_once (WCF_DIR . 'lib/data/message/bbcode/AttachmentBBCode.class.php');
				AttachmentBBCode::setAttachments($this->attachmentListEditor->getSortedAttachments());
				WCF::getTPL()->assign('preview', MpEntry::createPreview($this->subject, $this->text, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes));
			}
			// send message or save as draft
			if ($this->send) {
				$this->validate();
				// no errors
				$this->save();
			}
		} 

		catch (UserInputException $e) {
			$this->errorField = $e->getField();
			$this->errorType = $e->gettype();
		}
	}

	/**
	 *
	 * @see Form::validate()
	 */
	public function validate() {
		$countries = new Countries();
		$this->countries = $countries->get();
		
		$this->categories = new rmCategory(null);
		
		// username
		$this->validateUsername();
		
		$this->validateType();
		$this->validatePrice();
		if (RM_ENABLE_MAPS) {
			$this->validateCountry();
			$this->validateZipcode();
		}
		
		$this->validateCategory();
		// subject, text, captcha
		parent::validate();
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
	 * Validates message text.
	 */
	protected function validateText() {
		parent::validateText();
		
		// check text length
		if ($this->minCharLength > 0 && StringUtil::length($this->text) < $this->minCharLength) {
			throw new UserInputException('text', 'tooShort');
		}
		
		// check word count
		if ($this->minWordCount > 0 && count(preg_split('/[\W]+/', $this->text, -1, PREG_SPLIT_NO_EMPTY)) < $this->minWordCount) {
			throw new UserInputException('text', 'tooShort');
		}
	}

	/**
	 * Validates the message type
	 */
	protected function validateType() {
		if ($this->type != "search" && $this->type != "offer") {
			throw new UserInputException('type', 'empty');
		}
	}

	/**
	 * Validates the price if it is numeric
	 */
	protected function validatePrice() {
		if ($this->type == 'search')
			$settings = MP_PRICE_SETTINGS_SEARCH;
		else
			$settings = MP_PRICE_SETTINGS_OFFER;
		
		if ($settings == 'any')
			return;
		elseif ($settings == 'anyObligatory') {
			if (empty($this->price)) {
				throw new UserInputException('price', 'empty');
			}
		} elseif ($settings == 'number') {
			if (!empty($this->price) && !is_numeric(str_replace(',', '.', $this->price))) {
				throw new UserInputException('price', 'notNumeric');
			}
		} elseif ($settings == 'numberObligatory') {
			if (!is_numeric(str_replace(',', '.', $this->price))) {
				throw new UserInputException('price', 'notNumeric');
			}
		}
	}

	/**
	 * Validates the country
	 */
	protected function validateCountry() {
		if (!isset($this->countries [$this->country])) {
			throw new UserInputException('country');
		}
	}

	/**
	 * Validates the category
	 */
	protected function validateCategory() {
		if (!$this->categories->exists($this->categoryID)) {
			throw new UserInputException('category');
		}
	}

	/**
	 * Validates the given zipcode
	 */
	protected function validateZipcode() {
		if ($this->zipcode && strlen($this->zipcode) < 4) {
			throw new UserInputException('zipcode', 'wrong');
		}
	}

	/**
	 *
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		$this->entry = MpEntryEditor::add($this->subject, $this->text, WCF::getUser()->userID, $this->username, $this->type, $this->categoryID, $this->country, $this->zipcode, $this->price, $this->attachmentListEditor, $this->getOptions());
		
		// save tags
		if (defined('MODULE_TAGGING') && MODULE_TAGGING && RM_ENABLE_TAGS) {
			$tagArray = TaggingUtil::splitString($this->tags);
			if (count($tagArray))
				$this->entry->updateTags($tagArray);
		}
		
		if ($this->entry->userCanView()) {
			HeaderUtil::redirect('index.php?page=RMarketplaceEntry&entryID=' . $this->entry->entryID . SID_ARG_2ND_NOT_ENCODED);
			exit();
		} else {
			// @todo forward to other site to inform the user that the entry is
			// in moderation queue now
			// HeaderUtil::redirect('index.php?page=RMarketplace' .
			// SID_ARG_2ND_NOT_ENCODED);
			// exit();
			WCF::getTPL()->assign('saved', true);
		}
	}

	/**
	 *
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if (!count($_POST)) {
			$this->username = WCF::getSession()->username;
		}
		$countries = new Countries();
		$this->countries = $countries->get();
		
		$this->categories = new rmCategory(null);
		foreach ($this->categories->getCategories() as $category) {
			$this->catInfo [$category->catID] = str_replace(array (
					"\r", 
					"\r\n", 
					"\n" 
			), '', $category->catInfo);
		}
	}

	/**
	 *
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array (
				
				'username' => $this->username, 
				'countries' => $this->countries, 
				'categories' => $this->categories->getCategoriesSelect(), 
				'country' => $this->country, 
				'price' => $this->price, 
				'zipcode' => $this->zipcode, 
				'type' => $this->type, 
				'categoryID' => $this->categoryID, 
				'tags' => $this->tags, 
				'isCommentable' => $this->isCommentable, 
				'minCharLength' => $this->minCharLength, 
				'minWordCount' => $this->minWordCount, 
				'showPoll' => false, 
				'catInfo' => $this->catInfo 
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
		
		// set active header menu item
		PageMenu::setActiveMenuItem('wcf.header.menu.de.0xdefec.rmarketplace.header.menu');
		
		// get max text length
		$this->maxTextLength = WCF::getUser()->getPermission('user.rmarketplace.maxMessageLength');
		
		if (MODULE_ATTACHMENT != 1) {
			$this->showAttachments = false;
		}
		
		// get attachments editor
		if ($this->attachmentListEditor == null) {
			$this->attachmentListEditor = new MessageAttachmentListEditor(array (), 'rmentry', PACKAGE_ID, WCF::getUser()->getPermission('user.rmarketplace.maxAttachmentSize'), WCF::getUser()->getPermission('user.rmarketplace.allowedAttachmentExtensions'), WCF::getUser()->getPermission('user.rmarketplace.maxAttachmentCount'), $thumbnailWidth = RM_ATTACHMENT_THUMBNAIL_WIDTH, $thumbnailHeight = RM_ATTACHMENT_THUMBNAIL_HEIGHT, $addSourceInfo = RM_ATTACHMENT_THUMBNAIL_ADD_SOURCE_INFO);
		}
		
		// show form
		parent::show();
	}

	/**
	 * Returns the selected message options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		$options = parent::getOptions();
		$options ['isCommentable'] = ($this->isCommentable) ? true : false;
		return $options;
	}
}

?>