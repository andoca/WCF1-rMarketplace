ALTER TABLE wcf1_rmarketplace_entries ADD comments INT( 11 ) NOT NULL DEFAULT  '0';
ALTER TABLE wcf1_rmarketplace_entries ADD isCommentable INT( 1 ) NOT NULL DEFAULT  '1';

CREATE TABLE IF NOT EXISTS wcf1_rmarketplace_comments (
  commentID int(10) NOT NULL auto_increment,
  ownerID int(10) NOT NULL,
  entryID int(10) NOT NULL,
  userID int(10) NOT NULL default '0',
  username varchar(255) NOT NULL default '',
  comment text,
  `time` int(10) NOT NULL default '0',
  PRIMARY KEY  (`commentID`),
  KEY `entryID` (`entryID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;