<?php
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/countries/Countries.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/rmcategory/rmCategoryEditor.class.php');
require_once (WCF_DIR . 'lib/data/user/UserProfile.class.php');

/**
 * editor class to marketplace entries to manipulate them
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class MpEntryEditor extends MpEntry {

	/**
	 * adds a new entry to the database
	 *
	 * @param string $subject
	 * @param string $text
	 * @param string $type
	 * @param integer $category
	 * @param string $country
	 * @param string $zipcode
	 * @param string $price
	 * @param AttachmentListEditor $attachments
	 * @param array $options
	 * @return MpEntry the newly inserted MpEntry
	 */
	public static function add($subject, $text, $userID, $username, $type, $category, $country, $zipcode, $price, $attachments, $options) {
		$countries = new Countries();
		$countries = $countries->get();
		
		if (RM_ENABLE_MAPS && $zipcode && $countries [$country]) {
			$coord = GmapUtil::reverseGeocode(array (
					$zipcode, 
					$country 
			));
		} else
			$coord = array (
					'lat' => 'null', 
					'lng' => 'null' 
			);
		
		if ($attachments)
			$attachmentsAmount = $attachments != null ? count($attachments->getAttachments()) : 0;
		else
			$attachmentsAmount = 0;
		
		$sql = "INSERT INTO wcf" . WCF_N . "_rmarketplace_entries
				(
				subject,
				text,
				type,
				price,
				categoryID,
				zipcode,
				country,
				isDisabled,
				isActive,
				lat,
				lng,
				userID,
				username,
				time,
				ipAddress,
				attachments,
				enableSmilies,
				enableHtml,
				enableBBCodes,
				showSignature,
				isCommentable,
				insertTime
				)
			VALUES
				(
				'" . escapeString($subject) . "',
				'" . escapeString($text) . "',
				'" . escapeString($type) . "',
				'" . escapeString($price) . "',
				'" . intval($category) . "',
				'" . escapeString($zipcode) . "',
				'" . escapeString($country) . "',
				'" . (WCF::getUser()->getPermission('user.rmarketplace.canWriteWithoutModeration') ? 0 : 1) . "',
				'1',
				" . $coord ['lat'] . ",
				" . $coord ['lng'] . ",
				'" . intval($userID) . "',
				'" . escapeString($username) . "',
				'" . TIME_NOW . "',
				'" . UserUtil::getIpAddress() . "',
				'" . $attachmentsAmount . "',
				'" . intval($options ['enableSmilies']) . "',
				'" . intval($options ['enableHtml']) . "',
				'" . intval($options ['enableBBCodes']) . "',
				'" . intval($options ['showSignature']) . "',
				'" . intval($options ['isCommentable']) . "',
				'" . TIME_NOW . "'
				)";
		WCF::getDB()->sendQuery($sql);
		$entryID = WCF::getDB()->getInsertID();
		$newEntry = new MpEntryEditor($entryID);
		
		// assign attachments
		if ($attachments != null) {
			require_once (WCF_DIR . 'lib/data/message/bbcode/AttachmentBBCode.class.php');
			AttachmentBBCode::setAttachments($attachments->getSortedAttachments());
		}
		
		if ($attachments != null) {
			$attachments->updateContainerID($entryID);
			$attachments->findEmbeddedAttachments($text);
		}
		
		// clear category cache if needed (entry is enabled)
		if (WCF::getUser()->getPermission('user.rmarketplace.canWriteWithoutModeration')) {
			$category = new rmCategoryEditor($newEntry->categoryID);
			$category->addEntry();
			rmCategoryEditor::resetCache();
		}
		return $newEntry;
	}

	/**
	 * Updated an entry
	 *
	 * @param string $subject
	 * @param string $text
	 * @param string $type
	 * @param integer $category
	 * @param string $country
	 * @param string $zipcode
	 * @param string $price
	 * @param AttachmentEditor $attachments
	 * @param array $options
	 */
	public function update($subject, $text, $type, $category, $country, $zipcode, $price, $attachments, $options) {
		$countries = new Countries();
		$countries = $countries->get();
		
		if (RM_ENABLE_MAPS && $zipcode && $countries [$country]) {
			$coord = GmapUtil::reverseGeocode(array (
					$zipcode, 
					$country 
			));
		} else
			$coord = array (
					'lat' => 'null', 
					'lng' => 'null' 
			);
		
		$attachmentsAmount = $attachments != null ? count($attachments->getAttachments($this->entryID)) : 0;
		
		// assign attachments
		if ($attachments != null) {
			require_once (WCF_DIR . 'lib/data/message/bbcode/AttachmentBBCode.class.php');
			AttachmentBBCode::setAttachments($attachments->getSortedAttachments());
		}
		
		if (!WCF::getUser()->getPermission('user.rmarketplace.dontShowEditMessage')) {
			$editFields = ", editorName = '" . escapeString(WCF::getUser()->username) . "', editorID = '" . WCF::getUser()->userID . "', lastEditTime = " . TIME_NOW . ", editCount = editCount+1";
		} else
			$editFields = '';
		
		$user = new UserProfile($this->userID);
		
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_entries
				SET subject = '" . escapeString($subject) . "',
				text='" . escapeString($text) . "',
				type='" . escapeString($type) . "',
				price='" . escapeString($price) . "',
				categoryID='" . intval($category) . "',
				zipcode='" . escapeString($zipcode) . "'," . //				isDisabled='" . ! $user->getPermission('user.rmarketplace.canWriteWithoutModeration') . "',
"country='" . escapeString($country) . "',
				lat=" . $coord ['lat'] . ",
				lng=" . $coord ['lng'] . ",
				attachments='" . $attachmentsAmount . "',
				enableSmilies='" . intval($options ['enableSmilies']) . "',
				enableHtml='" . intval($options ['enableHtml']) . "',
				enableBBCodes='" . intval($options ['enableBBCodes']) . "',
				showSignature='" . intval($options ['showSignature']) . "',
				isCommentable='" . intval($options ['isCommentable']) . "'
				" . $editFields . "
			WHERE entryID = '" . intval($this->entryID) . "'
			";
		WCF::getDB()->sendQuery($sql);
		
		// update attachments
		if ($attachments != null) {
			$attachments->findEmbeddedAttachments($text);
		}
		
		// category is changed - update caches
		if ($this->categoryID != $category) {
			$categoryObj = new rmCategoryEditor($this->categoryID);
			if (!$this->isDisabled)
				$categoryObj->removeEntry();
			
		// also add to new category if entry is not disabled after edit
			if ($user->getPermission('user.rmarketplace.canWriteWithoutModeration')) {
				$newCategoryObj = new rmCategoryEditor($category);
				if (!$this->isDisabled)
					$newCategoryObj->addEntry();
			}
			
			rmCategoryEditor::resetCache();
		}
	}

	/**
	 * disables an entry (for moderation)
	 */
	public function disable($checkPermissions = true) {
		if (!$this->userCanEdit() && $checkPermissions)
			throw new PermissionDeniedException();
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_entries SET isDisabled = 1 WHERE entryID = '" . intval($this->entryID) . "'";
		WCF::getDB()->sendQuery($sql);
		
		$category = new rmCategoryEditor($this->categoryID);
		$category->removeEntry();
		
		rmCategoryEditor::resetCache();
	}

	/**
	 * enables an entry (for moderation)
	 */
	public function enable($checkPermissions = true) {
		if (!$this->userCanEdit() && $checkPermissions)
			throw new PermissionDeniedException();
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_entries SET isDisabled = 0 WHERE entryID = '" . intval($this->entryID) . "'";
		WCF::getDB()->sendQuery($sql);
		
		$category = new rmCategoryEditor($this->categoryID);
		$category->addEntry();
		
		rmCategoryEditor::resetCache();
	}

	/**
	 * deactivates an entry (marked as done)
	 */
	public function deactivate($checkPermissions = true) {
		if (!$this->userCanDeactivate() && $checkPermissions)
			throw new PermissionDeniedException();
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_entries SET isActive = 0 WHERE entryID = '" . intval($this->entryID) . "'";
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * activates an entry (marked as not done)
	 */
	public function activate($checkPermissions = true) {
		if (!$this->userCanDeactivate() && $checkPermissions)
			throw new PermissionDeniedException();
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_entries SET isActive = 1 WHERE entryID = '" . intval($this->entryID) . "'";
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Deletes an entry completely with all attachments
	 */
	public function delete($checkPermissions = true) {
		if (!$this->userCanDelete() && $checkPermissions)
			throw new PermissionDeniedException();
		
		require_once (WCF_DIR . 'lib/data/attachment/MessageAttachmentListEditor.class.php');
		$attachment = new MessageAttachmentListEditor(array (
				$this->entryID 
		), 'rmentry');
		$attachment->deleteAll();
		
		// delete comments
		require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/comment/RMarketplaceEntryCommentList.class.php');
		require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/comment/RMarketplaceEntryCommentEditor.class.php');
		$commentList = new RMarketplaceEntryCommentList();
		$commentList->sqlConditions .= 'rmarketplace_comments.entryID = ' . $this->entryID;
		$commentList->readObjects();
		$comments = $commentList->getObjects();
		foreach ($comments as $comment) {
			$commentEditor = new RMarketplaceEntryCommentEditor($comment->commentID);
			$commentEditor->delete();
		}
		
		$sql = "DELETE FROM wcf" . WCF_N . "_rmarketplace_entries WHERE entryID = '" . intval($this->entryID) . "'";
		WCF::getDB()->sendQuery($sql);
		
		// delete tags
		if (defined('MODULE_TAGGING') && MODULE_TAGGING) {
			require_once (WCF_DIR . 'lib/data/tag/TagEngine.class.php');
			$taggable = TagEngine::getInstance()->getTaggable('de.0xdefec.rmarketplace.mpentry');
			
			$sql = "DELETE FROM	wcf" . WCF_N . "_tag_to_object
				WHERE 		taggableID = " . $taggable->getTaggableID() . "
						AND objectID IN (" . intval($this->entryID) . ")";
			WCF::getDB()->registerShutdownUpdate($sql);
		}
		
		if (!$this->isDisabled) {
			$category = new rmCategoryEditor($this->categoryID);
			$category->removeEntry();
		}
		
		rmCategoryEditor::resetCache();
	}

	/**
	 * Push the entry up on the list (update date to time_now)
	 *
	 */
	public function push($checkPermissions = true) {
		if (!$this->userCanPush() && $checkPermissions)
			throw new PermissionDeniedException();
		
		if ($this->time == $this->insertTime)
			$updateInsertTime = 'insertTime = time,';
		else
			$updateInsertTime = '';
		
		$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_entries
					SET 
					" . $updateInsertTime . "
					time = '" . TIME_NOW . "',
					pushCount = pushCount+1
				WHERE entryID = '" . intval($this->entryID) . "'";
		WCF::getDB()->sendQuery($sql);
	}

	/**
	 * Send notification to the owner that his entry will expire soon
	 *
	 */
	public function sendExpireNotification() {
		if (MODULE_PM)
			require_once (WCF_DIR . 'lib/data/message/pm/PMEditor.class.php');
		require_once (WCF_DIR . 'lib/data/user/UserProfile.class.php');
		
		$recipient = new UserProfile($this->userID);
		
		$notificationRecipients = array ();
		
		// send pm notification to owner of the entry
		if (MODULE_PM && $recipient->getUserOption('settings.communication.rmarketplace.pmOnExpiringEntry')) {
			// check if we can send this user a pm
			if ($recipient->getPermission('user.pm.canUsePm') && $recipient->acceptPm) {
				// check if the users wants to be notified
				if ($recipient->emailOnPm) {
					$notificationRecipients [$recipient->userID] = $recipient;
				}
				
				$this->recipientArray = array (
						array (
								'userID' => $recipient->userID, 
								'username' => $recipient->username 
						) 
				);
				
				// get the pm content
				

				$this->languages = array (
						0 => WCF::getLanguage(), 
						WCF::getLanguage()->getLanguageID() => WCF::getLanguage() 
				);
				// get language
				if (!isset($this->languages [$recipient->languageID])) {
					$this->languages [$recipient->languageID] = new Language($recipient->languageID);
				}
				
				// enable language
				$this->languages [$recipient->languageID]->setLocale();
				
				if ($this->userCanPush($this->userID))
					$pmData = array (
							'subject' => $this->languages [$recipient->languageID]->get("de.0xdefec.rmarketplace.expiringEntry.canPush.subject"), 
							'text' => $this->languages [$recipient->languageID]->get("de.0xdefec.rmarketplace.expiringEntry.canPush.text") 
					);
				else
					$pmData = array (
							'subject' => $this->languages [$recipient->languageID]->get("de.0xdefec.rmarketplace.expiringEntry.noPush.subject"), 
							'text' => $this->languages [$recipient->languageID]->get("de.0xdefec.rmarketplace.expiringEntry.noPush.text") 
					);
				
				$expirationDate = DateUtil::formatShortTime(null, $this->time + (MP_ENTRY_OLD_TIME * 60 * 60 * 24), false);
				
				// replace variables in the pm subject and text
				foreach ($pmData as $idx=>$str) {
					$pmData [$idx] = str_replace(array (
							'{$userID}', 
							'{$username}', 
							'{$entryID}', 
							'{$expirationDate}' 
					), array (
							WCF::getUser()->userID, 
							WCF::getUser()->username, 
							$this->entryID, 
							$expirationDate 
					), $str);
				}
				
				$this->pmText = $pmData ['text'];
				$this->pmSubject = $pmData ['subject'];
				
				$sender = new User(MP_SENDERID);
				if ($sender->userID) {
					$senderID = $sender->userID;
					$senderName = $sender->username;
				} else {
					$senderID = 0;
					$senderName = 'Nobody';
				}
				
				$this->newPm = PMEditor::create(false, $this->recipientArray, array (), $this->pmSubject, $this->pmText, $senderID, $senderName);
				
				// send e-mail notification
				if (count($notificationRecipients) > 0) {
					$this->newPm->sendNotifications($notificationRecipients);
				}
				
				// apply rules
				$this->newPm->applyRules();
				
				// save that we have informed
				$sql = "UPDATE wcf" . WCF_N . "_rmarketplace_entries
						SET notificationCount = notificationCount+1
						WHERE entryID = " . $this->entryID;
				WCF::getDB()->sendQuery($sql);
				
				// enable user language
				WCF::getLanguage()->setLocale();
			}
		}
	}

	/**
	 * Updates the tags of this entry
	 *
	 * @param	array		$tags
	 */
	public function updateTags($tagArray) {
		// include files
		require_once (WCF_DIR . 'lib/data/tag/TagEngine.class.php');
		require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/TaggedMpEntry.class.php');
		
		// save tags
		$tagged = new TaggedMpEntry(null, array (
				'entryID' => $this->entryID, 
				'taggable' => TagEngine::getInstance()->getTaggable('de.0xdefec.rmarketplace.mpentry') 
		));
		
		// delete old tags
		TagEngine::getInstance()->deleteObjectTags($tagged, array (
				0 
		));
		
		// save new tags
		if (count($tagArray) > 0)
			TagEngine::getInstance()->addTags($tagArray, $tagged, 0);
	}
}

?>