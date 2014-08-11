<?php
// wcf imports
require_once(WCF_DIR.'lib/data/user/infraction/warning/object/WarningObjectType.class.php');
require_once(WCF_DIR.'lib/data/rmarketplace/mpEntry/MpEntryWarningObject.class.php');

/**
 * An implementation of WarningObjectType to support the usage of a mpEntry as a warning object.
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class MpEntryWarningObjectType implements WarningObjectType {
	/**
	 * @see WarningObjectType::getObjectByID()
	 */
	public function getObjectByID($objectID) {
		if (is_array($objectID)) {
			$entries = array();
			$sql = "SELECT		rmarketplace.*
				FROM 		wcf".WCF_N."_rmarketplace_entries rmarketplace
				WHERE 		entryID IN (".implode(',', $objectID).")";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$entries[$row['entryID']] = new MpEntryWarningObject(null, $row);
			}

			return (count($entries) > 0 ? $entries : null);
		}
		else {
			// get object
			$entry = new MpEntryWarningObject($objectID);
			if (!$entry->entryID) return null;

			// return object
			return $entry;
		}
	}
}
?>