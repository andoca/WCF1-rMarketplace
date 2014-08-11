<?php
// wcf imports
require_once(WCF_DIR.'lib/data/rmarketplace/mpEntry/MpEntryList.class.php');
require_once(WCF_DIR.'lib/data/tag/TagEngine.class.php');

/**
 * Represents a list of tagged rMarketplace entries
 *
 * @author	Andreas Diendorfer
 * @copyright	Andreas Diendorfer
 * @license	Proprietary license - see http://www.selbstzweck.net
 * @package	de.0xdefec.rMarketplace
 */
class TaggedMpEntryList extends MpEntryList {
	/**
	 * tag id
	 *
	 * @var	integer
	 */
	public $tagID = 0;

	/**
	 * taggable object
	 *
	 * @var	Taggable
	 */
	public $taggable = null;

	/**
	 * Creates a new TaggedMpEntryListList object.
	 */
	public function __construct($tagID) {
		$this->tagID = $tagID;
		$this->taggable = TagEngine::getInstance()->getTaggable('de.0xdefec.rmarketplace.mpentry');
	}

	/**
	 * @see DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		if (!empty($this->sqlConditions)) {
			$sql = "SELECT	COUNT(*) AS count
				FROM	wcf".WCF_N."_tag_to_object tag_to_object,
					wcf".WCF_N."_rmarketplace_entries rmarketplace
				WHERE	tag_to_object.tagID = ".$this->tagID."
					AND tag_to_object.taggableID = ".$this->taggable->getTaggableID()."
					AND rmarketplace.entryID = tag_to_object.objectID
					AND ".$this->sqlConditions;
		}
		else {
			$sql = "SELECT	COUNT(*) AS count
				FROM	wcf".WCF_N."_tag_to_object
				WHERE	tagID = ".$this->tagID."
					AND taggableID = ".$this->taggable->getTaggableID();
		}
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}

	/**
	 * @see DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		$sql = "SELECT	".(!empty($this->sqlSelects) ? $this->sqlSelects.',' : '')."
				rmarketplace.*
			FROM	wcf".WCF_N."_tag_to_object tag_to_object,
				wcf".WCF_N."_rmarketplace_entries rmarketplace
			".$this->sqlJoins."
			WHERE	tag_to_object.tagID = ".$this->tagID."
				AND tag_to_object.taggableID = ".$this->taggable->getTaggableID()."
				AND rmarketplace.entryID = tag_to_object.objectID
				".(!empty($this->sqlConditions) ? "AND ".$this->sqlConditions : '')."
				".(!empty($this->sqlOrderBy) ? "ORDER BY ".$this->sqlOrderBy : '');
		$result = WCF::getDB()->sendQuery($sql, $this->sqlLimit, $this->sqlOffset);
		while ($row = WCF::getDB()->fetchArray($result)) {
			$this->entries[] = new MpEntry(null, $row);
		}
	}
}
?>