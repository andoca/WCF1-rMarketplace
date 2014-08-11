<?php
// wcf imports
require_once(WCF_DIR.'lib/data/tag/AbstractTaggableObject.class.php');
require_once(WCF_DIR.'lib/data/rmarketplace/mpEntry/TaggedMpEntry.class.php');

/**
 * implemention of the taggable object for MpEntry
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class TaggableMpEntry extends AbstractTaggableObject {
	/**
	 * @see Taggable::getObjectsByIDs()
	 */
	public function getObjectsByIDs($objectIDs, $taggedObjects) {
		$sql = "SELECT		*
			FROM		wcf".WCF_N."_rmarketplace_entries
			WHERE		entryID IN (" . implode(",", $objectIDs) . ")
				AND isDisabled = 0
			";
		$result = WCF::getDB()->sendQuery($sql);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$row['taggable'] = $this;
			$taggedObjects[] = new TaggedMpEntry(null, $row);
		}
		return $taggedObjects;
	}

	/**
	 * @see Taggable::countObjectsByTagID()
	 */
	public function countObjectsByTagID($tagID) {
		$sql = "SELECT		COUNT(*) AS count
			FROM		wcf".WCF_N."_tag_to_object tag_to_object
			LEFT JOIN	wcf".WCF_N."_rmarketplace_entries rmarketplace
			ON		(rmarketplace.entryID = tag_to_object.objectID)
			WHERE 		tag_to_object.tagID = ".$tagID."
					AND tag_to_object.taggableID = ".$this->getTaggableID()."
					AND rmarketplace.isDisabled = 0";
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}

	/**
	 * @see Taggable::getObjectsByTagID()
	 */
	public function getObjectsByTagID($tagID, $limit = 0, $offset = 0) {
		$entries = array();
		$sql = "SELECT		rmarketplace.*,	user_table.username
			FROM		wcf".WCF_N."_tag_to_object tag_to_object
			LEFT JOIN	wcf".WCF_N."_rmarketplace_entries rmarketplace
			ON		(rmarketplace.entryID = tag_to_object.objectID)
			LEFT JOIN	wcf".WCF_N."_user user_table
			ON		(user_table.userID = rmarketplace.userID)
			WHERE		tag_to_object.tagID = ".$tagID."
					AND tag_to_object.taggableID = ".$this->getTaggableID()."
					AND rmarketplace.isDisabled = 0
			ORDER BY	rmarketplace.time DESC";
		$result = WCF::getDB()->sendQuery($sql, $limit, $offset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$row['taggable'] = $this;
			$entries[] = new TaggedMpEntry(null, $row);
		}
		return $entries;
	}

	/**
	 * @see Taggable::getIDFieldName()
	 */
	public function getIDFieldName() {
		return 'entryID';
	}

	/**
	 * @see Taggable::getResultTemplateName()
	 */
	public function getResultTemplateName() {
		return 'taggedMpEntry';
	}

	/**
	 * @see Taggable::getSmallSymbol()
	 */
	public function getSmallSymbol() {
		return StyleManager::getStyle()->getIconPath('rMarketplaceS.png');
	}

	/**
	 * @see Taggable::getMediumSymbol()
	 */
	public function getMediumSymbol() {
		return StyleManager::getStyle()->getIconPath('rMarketplaceM.png');
	}

	/**
	 * @see Taggable::getLargeSymbol()
	 */
	public function getLargeSymbol() {
		return StyleManager::getStyle()->getIconPath('rMarketplaceL.png');
	}
}
?>