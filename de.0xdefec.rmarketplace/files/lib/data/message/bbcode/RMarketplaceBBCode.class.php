<?php
require_once (WCF_DIR . 'lib/data/message/bbcode/BBCodeParser.class.php');
require_once (WCF_DIR . 'lib/data/message/bbcode/BBCode.class.php');
require_once (WCF_DIR . 'lib/data/rmarketplace/mpEntry/MpEntry.class.php');

/**
 * Parses the [mp] bbcode tag.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class RMarketplaceBBCode implements BBCode {
	/**
	 * @see BBCode::getParsedTag()
	 */
	public function getParsedTag($openingTag, $content, $closingTag, BBCodeParser $parser) {
		$entry = new MpEntry(intval($content));
		if (! $entry->entryID) return intval($content);

		WCF::getTPL()->assign(array(
				'entries' => array(
						$entry
				)
		));
		return WCF::getTPL()->fetch('rmarketplaceEntryList');
	}
}
?>