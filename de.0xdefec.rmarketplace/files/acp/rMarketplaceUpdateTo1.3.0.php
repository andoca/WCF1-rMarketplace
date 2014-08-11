<?php
/**
 * Update styles
 */
require_once (WCF_DIR . 'lib/data/style/StyleEditor.class.php');

$styles = Style::getStyles();

foreach ( $styles as $style ) {
	$styleEditor = new StyleEditor($style->styleID);
	$styleEditor->writeStyleFile();
}
?>