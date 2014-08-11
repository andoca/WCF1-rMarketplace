DROP TABLE IF EXISTS wcf1_rmarketplace_entries;
CREATE TABLE wcf1_rmarketplace_entries (
	  entryID int(11) UNSIGNED NOT NULL auto_increment,
	  userID int(11) UNSIGNED NOT NULL,
	  username VARCHAR( 255 ) NOT NULL,
	  `subject` varchar(255) NOT NULL,
	  `text` text NOT NULL,
	  `type` varchar(48) NOT NULL,
	  price varchar(255) NOT NULL,
	  categoryID INT( 11 ) UNSIGNED NOT NULL,
	  zipcode VARCHAR( 6 ) NOT NULL,
	  country varchar(2) NOT NULL,
	  isDisabled tinyint(1) UNSIGNED NOT NULL default '0',
	  isActive tinyint(1) UNSIGNED NOT NULL default '1',
	  lat double default NULL,
	  lng double default NULL,
	  `time` int(11) UNSIGNED NOT NULL,
	  ipAddress varchar(15) NOT NULL,
	  attachments smallint(5) NOT NULL,
	  enableSmilies tinyint(1) UNSIGNED NOT NULL default '1',
	  enableHtml tinyint(1) UNSIGNED NOT NULL default '0',
	  enableBBCodes tinyint(1) UNSIGNED NOT NULL default '1',
	  showSignature tinyint(1) UNSIGNED NOT NULL default '1',
	  clicks int(11) UNSIGNED NOT NULL default '0',
	  editorName VARCHAR( 255 ) NOT NULL default '',
	  editorID INT( 11 ) UNSIGNED NOT NULL default '0',
	  lastEditTime INT( 11 ) UNSIGNED NOT NULL default '0',
	  editCount INT( 11 ) UNSIGNED NOT NULL default '0',
	  comments INT( 11 ) UNSIGNED NOT NULL DEFAULT  '0',
	  isCommentable INT( 1 ) NOT NULL DEFAULT  '1',
	  pushCount INT( 11 ) UNSIGNED NOT NULL default '0',
	  notificationCount INT( 11 ) UNSIGNED NOT NULL DEFAULT '0',
	  insertTime INT(11),
	  PRIMARY KEY  (entryID),
	  KEY userID (userID),
	  KEY categoryID (categoryID),
	  KEY type (type),
	  KEY isDisabled (isDisabled),
	  KEY isActive (isActive),
	  FULLTEXT subject (
           subject,
           text,
           country,
           zipcode
      )
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_rmarketplace_comments;
CREATE TABLE IF NOT EXISTS wcf1_rmarketplace_comments (
  commentID int(11) UNSIGNED NOT NULL auto_increment,
  ownerID int(11) UNSIGNED NOT NULL,
  entryID int(11) UNSIGNED NOT NULL,
  userID int(11) UNSIGNED NOT NULL default '0',
  username varchar(255) NOT NULL default '',
  comment text,
  `time` int(10) NOT NULL default '0',
  PRIMARY KEY  (commentID),
  KEY entryID (entryID)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_rmarketplace_categories;
CREATE TABLE wcf1_rmarketplace_categories (
	catID INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
	catParent INT( 11 ) UNSIGNED NOT NULL,
	catOrder INT( 11 ) UNSIGNED NOT NULL default '0',
	catName VARCHAR( 255 ) NOT NULL ,
	catIcon VARCHAR( 255 ) NOT NULL default '' ,
	catInfo TEXT NOT NULL,
	catDescription TEXT NOT NULL,
	entries INT( 11 ) UNSIGNED NOT NULL default '0',
    KEY catParent (catParent)
) ENGINE = MYISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS wcf1_rmarketplace_visit;
CREATE TABLE IF NOT EXISTS wcf1_rmarketplace_visit (
  userID int(11) unsigned NOT NULL default '0',
  lastVisitTime int(11) unsigned NOT NULL default '0',
  lastActivityTime int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (userID),
  UNIQUE KEY userID (userID,lastVisitTime),
  UNIQUE KEY userID_2 (userID,lastActivityTime)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO wcf1_rmarketplace_categories (
		catID ,
		catParent ,
		catOrder ,
		catName ,
		catIcon ,
		catInfo ,
		catDescription ,
		entries
	)
	VALUES (
		NULL, 
		'0', 
		'0', 
		'Allgemeines',
		'',
		'',
		'Standardkategorie',
		'0');