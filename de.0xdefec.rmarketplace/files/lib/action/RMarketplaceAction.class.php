<?php
// wcf imports
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntryEditor.class.php');
require_once (WCF_DIR . 'lib/action/AbstractSecureAction.class.php');

/**
 * Action Methode to do stuff with entries that does not need output
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceAction extends AbstractSecureAction {

	public $methode = null;

	public $entryID = null;

	/**
	 * @see Action::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		if (isset($_REQUEST ['methode']))
			$this->methode = StringUtil::trim($_REQUEST ['methode']);
		if (isset($_REQUEST ['entryID']))
			$this->entryID = intval($_REQUEST ['entryID']);
		
		if ($this->methode == null)
			throw new IllegalLinkException();
	}

	/**
	 * @see Action::execute()
	 */
	public function execute() {
		parent::execute();
		
		if ($this->entryID) {
			$entry = new MpEntryEditor($this->entryID);
			if (!$entry->entryID)
				throw new IllegalLinkException();
			
			if ($this->methode == "delete") {
				$entry->delete();
				HeaderUtil::redirect('index.php?page=RMarketplace' . SID_ARG_2ND_NOT_ENCODED);
				exit();
			} else if ($this->methode == "disable") {
				$entry->disable();
				if (isset($_SERVER ['HTTP_REFERER']) && $_SERVER ['HTTP_REFERER'])
					HeaderUtil::redirect($_SERVER ['HTTP_REFERER'], false);
				else
					HeaderUtil::redirect("index.php?page=RMarketplaceEntry&entryID=" . $entry->entryID . SID_ARG_2ND_NOT_ENCODED, false);
				exit();
			} else if ($this->methode == "enable") {
				$entry->enable();
				if (isset($_SERVER ['HTTP_REFERER']) && $_SERVER ['HTTP_REFERER'])
					HeaderUtil::redirect($_SERVER ['HTTP_REFERER'], false);
				else
					HeaderUtil::redirect("index.php?page=RMarketplaceEntry&entryID=" . $entry->entryID . SID_ARG_2ND_NOT_ENCODED, false);
				exit();
			} else if ($this->methode == "activate") {
				$entry->activate();
				if (isset($_SERVER ['HTTP_REFERER']) && $_SERVER ['HTTP_REFERER'])
					HeaderUtil::redirect($_SERVER ['HTTP_REFERER'], false);
				else
					HeaderUtil::redirect("index.php?page=RMarketplaceEntry&entryID=" . $entry->entryID . SID_ARG_2ND_NOT_ENCODED, false);
				exit();
			} else if ($this->methode == "deactivate") {
				$entry->deactivate();
				if (isset($_SERVER ['HTTP_REFERER']) && $_SERVER ['HTTP_REFERER'])
					HeaderUtil::redirect($_SERVER ['HTTP_REFERER'], false);
				else
					HeaderUtil::redirect("index.php?page=RMarketplaceEntry&entryID=" . $entry->entryID . SID_ARG_2ND_NOT_ENCODED, false);
				exit();
			} else if ($this->methode == "push") {
				$entry->push();
				if (isset($_SERVER ['HTTP_REFERER']) && $_SERVER ['HTTP_REFERER'])
					HeaderUtil::redirect($_SERVER ['HTTP_REFERER'], false);
				else
					HeaderUtil::redirect("index.php?page=RMarketplaceEntry&entryID=" . $entry->entryID . SID_ARG_2ND_NOT_ENCODED, false);
				exit();
			} else {
				throw new IllegalLinkException();
			}
		} else {
			if ($this->methode == "markAllRead") {
				$this->updateLastVisitTime();
				if (isset($_SERVER ['HTTP_REFERER']) && $_SERVER ['HTTP_REFERER'])
					HeaderUtil::redirect($_SERVER ['HTTP_REFERER'], false);
				else
					HeaderUtil::redirect("index.php?page=RMarketplace" . SID_ARG_2ND_NOT_ENCODED, false);
				exit();
			} else {
				throw new IllegalLinkException();
			}
		}
	}

	public function updateLastVisitTime() {
		// save the new lastVisitTime to Session and DB
		if (WCF::getUser()->userID) {
			$sql = "INSERT INTO wcf" . WCF_N . "_rmarketplace_visit
							(userID, lastVisitTime)
						VALUES
							(" . WCF::getUser()->userID . ", " . TIME_NOW . ")
					ON DUPLICATE KEY UPDATE
						lastVisitTime = " . TIME_NOW;
			WCF::getDB()->sendQuery($sql);
		}
		WCF::getSession()->register('rmLastVisitTime', TIME_NOW);
		
		// force a reload of the menu bar indicator by resetting its values
		$currentNew = array (
				'lastUpdateTime' => 0, 
				'count' => 0 
		);
		WCF::getSession()->register('rmCurrentNew', $currentNew);
	}
}
?>