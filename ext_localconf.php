<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

	// adding the tx_gabasics-fields to the pageOverlayFields so it is recognized when fetching the overlay fields
$TYPO3_CONF_VARS['FE']['pageOverlayFields'] .= ',tx_gabasics_disabletracking,tx_gabasics_pageviewurl,tx_gabasics_donottrackpageview,tx_gabasics_additionaljscode';

t3lib_extMgm::addPItoST43($_EXTKEY, 'pi1/class.tx_gabasics_pi1.php', '_pi1', 'list_type', 1);


	// adding the link processor to the typolink function
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typoLink_PostProc']['tx_gabasics'] =
	'EXT:ga_basics/class.tx_gabasics.php:&tx_gabasics->processAllLinks';

?>