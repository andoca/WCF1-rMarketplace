<?php

/**
 * install the andoca update servers
 */

// check if a server is currently installed and there are usernames and passwords
$sql = "SELECT count(*) as servers FROM wcf" . WCF_N . "_package_update_server WHERE server LIKE 'http://www.andoca.de/update/'";
$updateServer = WCF::getDB()->getFirstRow($sql);

if ($updateServer ['servers'] == 0) {
	// add the server
	$sql = "INSERT INTO wcf" . WCF_N . "_package_update_server
			(packageUpdateServerID, 
				server, 
				status, 
				statusUpdate, 
				errorText, 
				updatesFile, 
				timestamp, 
				htUsername, 
				htPassword)
		VALUES
			(NULL, 
				'http://www.andoca.de/update/', 
				'online', 
				1, 
				NULL, 
				0, 
				1293840000, 
				'', 
				'');
		";
	WCF::getDB()->sendQuery($sql);
}


?>