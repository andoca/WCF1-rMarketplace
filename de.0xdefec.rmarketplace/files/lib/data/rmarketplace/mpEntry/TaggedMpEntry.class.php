<?php
// wcf imports
require_once(WCF_DIR.'lib/data/tag/Tagged.class.php');
require_once(WCF_DIR.'lib/data/user/User.class.php');
require_once(WCF_DIR.'lib/data/rmarketplace/mpEntry/MpEntry.class.php');

/**
 * implemention of the tagged class for MpEntry
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */

class TaggedMpEntry extends MpEntry implements Tagged {
	/**
	 * user object
	 *
	 * @var	User
	 */
	public $user = null;

	/**
	 * @see ViewableThread::handleData()
	 */
	protected function handleData($data) {
		parent::handleData($data);

		// get user
		$this->user = new User(null, array('userID' => $this->userID, 'username' => $this->username));
	}

	/**
	 * @see Tagged::getTitle()
	 */
	public function getTitle() {
		return $this->subject;
	}

	/**
	 * @see Tagged::getObjectID()
	 */
	public function getObjectID() {
		return $this->entryID;
	}

	/**
	 * @see Tagged::getTaggable()
	 */
	public function getTaggable() {
		return $this->taggable;
	}

	/**
	 * @see Tagged::getDescription()
	 */
	public function getDescription() {
		return $this->text;
	}

	/**
	 * @see Tagged::getSmallSymbol()
	 */
	public function getSmallSymbol() {
		return StyleManager::getStyle()->getIconPath('rMarketplaceS.png');
	}

	/**
	 * @see Tagged::getMediumSymbol()
	 */
	public function getMediumSymbol() {
		return StyleManager::getStyle()->getIconPath('rMarketplaceM.png');
	}

	/**
	 * @see Tagged::getLargeSymbol()
	 */
	public function getLargeSymbol() {
		return StyleManager::getStyle()->getIconPath('rMarketplaceL.png');
	}

	/**
	 * @see Tagged::getUser()
	 */
	public function getUser() {
		return $this->user;
	}

	/**
	 * @see Tagged::getDate()
	 */
	public function getDate() {
		return $this->time;
	}

	/**
	 * @see Tagged::getDate()
	 */
	public function getURL() {
		return 'index.php?page=RMarketplaceEntry&entryID='.$this->entryID;
	}
}
?>