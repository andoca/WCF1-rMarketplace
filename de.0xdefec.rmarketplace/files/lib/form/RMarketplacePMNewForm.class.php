<?php
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');
if (MODULE_PM) require_once (WCF_DIR . 'lib/form/PMNewForm.class.php');

/**
 * Form for creating a new private message out of the marketplace
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplacePMNewForm extends PMNewForm {
	private $entryID = null;
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		if (!MODULE_PM) throw new IllegalLinkException();
		
		if (isset($_GET['entryID'])) $this->entryID = intval($_GET['entryID']);
		parent::readParameters();
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		if ($this->entryID) {
			$entry = new MpEntry($this->entryID);
			if ($entry->entryID) {
				$this->subject = WCF::getLanguage()->get('de.0xdefec.rmarketplace.pnNew.subject') . $entry->subject;
			}
		}
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
				'subject' => $this->subject
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