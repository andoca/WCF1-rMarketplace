DROP TABLE IF EXISTS wcf1_rmarketplace_visit;
CREATE TABLE wcf1_rmarketplace_visit (
  userID int(11) unsigned NOT NULL,
  lastVisitTime int(11) unsigned NOT NULL,
  lastActivityTime int(11) unsigned NOT NULL,
  PRIMARY KEY  (userID),
  UNIQUE KEY userID (userID,lastVisitTime),
  UNIQUE KEY userID_2 (userID,lastActivityTime)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
