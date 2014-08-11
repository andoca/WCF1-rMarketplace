<?php
/**
 * rMarketplace installation script
 */

$packageID = $this->installation->getPackageID();

/**
 * Update styles
 */
require_once (WCF_DIR . 'lib/data/style/StyleEditor.class.php');

$styles = Style::getStyles();

foreach ( $styles as $style ) {
	$styleEditor = new StyleEditor($style->styleID);
	$styleEditor->writeStyleFile();
}

/**
 * Set default permissions
 */

// for admin users (groupID = 4)
$sql = "UPDATE wcf" . WCF_N . "_group_option_value
			SET	optionValue = 1
			WHERE groupID = 4
				AND optionID IN (
					SELECT optionID FROM wcf" . WCF_N . "_group_option
						WHERE packageID = " . $packageID . "
						AND optionType = 'boolean'
				)
				AND optionValue = '0'";
WCF::getDB()->sendQuery($sql);

// for mods and supermods (groupID 5 and 6)
$sql = "UPDATE wcf" . WCF_N . "_group_option_value
			SET	optionValue = 1
			WHERE groupID IN (5,6)
				AND optionID IN (
					SELECT optionID FROM wcf" . WCF_N . "_group_option
						WHERE optionName LIKE 'mod.rmarketplace.%'
						AND packageID = " . $packageID . "
						AND optionType = 'boolean'
				)
				AND optionValue = '0'";
WCF::getDB()->sendQuery($sql);

// install andoca update server
require_once (WCF_DIR . 'acp/andocaUpdateServer.php');

?>