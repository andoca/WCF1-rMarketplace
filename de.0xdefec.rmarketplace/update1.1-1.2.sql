ALTER TABLE wcf1_rmarketplace_categories ADD catInfo TEXT NOT NULL AFTER catIcon;
ALTER TABLE wcf1_rmarketplace_categories ADD entries INT( 11 ) UNSIGNED NOT NULL;
UPDATE wcf1_rmarketplace_categories category SET entries = (SELECT COUNT(*) FROM wcf1_rmarketplace_entries WHERE categoryID = category.catID);
ALTER TABLE wcf1_rmarketplace_entries ADD pushCount INT(11) UNSIGNED NOT NULL;
ALTER TABLE wcf1_rmarketplace_entries ADD insertTime INT(11) UNSIGNED NOT NULL;
ALTER TABLE wcf1_rmarketplace_entries ADD notificationCount INT( 11 ) UNSIGNED NOT NULL DEFAULT '0';

ALTER TABLE wcf1_rmarketplace_entries ADD INDEX (userID);
ALTER TABLE wcf1_rmarketplace_entries ADD INDEX (categoryID);
ALTER TABLE wcf1_rmarketplace_entries ADD INDEX (type);
ALTER TABLE wcf1_rmarketplace_entries ADD INDEX (isDisabled);
ALTER TABLE wcf1_rmarketplace_entries ADD INDEX (isActive);
ALTER TABLE wcf1_rmarketplace_categories ADD INDEX (catParent);

DROP TABLE IF EXISTS wcf1_rmarketplace_visit;
CREATE TABLE wcf1_rmarketplace_visit (
  userID int(11) unsigned NOT NULL,
  lastVisitTime int(11) unsigned NOT NULL,
  lastActivityTime int(11) unsigned NOT NULL,
  PRIMARY KEY  (userID),
  UNIQUE KEY userID (userID,lastVisitTime),
  UNIQUE KEY userID_2 (userID,lastActivityTime)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
