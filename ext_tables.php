<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}
$tempColumns = array (
	'tx_gabasics_disabletracking' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:ga_basics/locallang_db.xml:pages.tx_gabasics_disabletracking',		
		'config' => array (
			'type' => 'check',
		)
	),
	'tx_gabasics_pageviewurl' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:ga_basics/locallang_db.xml:pages.tx_gabasics_pageviewurl',		
		'config' => array (
			'type' => 'input',	
			'size' => '48',	
			'max' => '255',
		)
	),
	'tx_gabasics_donottrackpageview' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:ga_basics/locallang_db.xml:pages.tx_gabasics_donottrackpageview',		
		'config' => array (
			'type' => 'check',
		)
	),
	'tx_gabasics_additionaljscode' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:ga_basics/locallang_db.xml:pages.tx_gabasics_additionaljscode',		
		'config' => array (
			'type' => 'text',
			'cols' => '30',	
			'rows' => '5',
		)
	),
);


t3lib_div::loadTCA('pages');
t3lib_extMgm::addTCAcolumns('pages',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('pages','--div--;Tracking, tx_gabasics_disabletracking;;;;1-1-1, tx_gabasics_pageviewurl, tx_gabasics_donottrackpageview, tx_gabasics_additionaljscode', '1');

$tempColumns = array (
	'tx_gabasics_disabletracking' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:ga_basics/locallang_db.xml:pages_language_overlay.tx_gabasics_disabletracking',		
		'config' => array (
			'type' => 'check',
		)
	),
	'tx_gabasics_pageviewurl' => array (		
		'exclude' => 1,		
		'label' => 'LLL:EXT:ga_basics/locallang_db.xml:pages_language_overlay.tx_gabasics_pageviewurl',		
		'config' => array (
			'type' => 'input',	
			'size' => '48',	
			'max' => '255',
		)
	),
	'tx_gabasics_donottrackpageview' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:ga_basics/locallang_db.xml:pages_language_overlay.tx_gabasics_donottrackpageview',		
		'config' => array (
			'type' => 'check',
		)
	),
	'tx_gabasics_additionaljscode' => array (		
		'exclude' => 0,		
		'label' => 'LLL:EXT:ga_basics/locallang_db.xml:pages_language_overlay.tx_gabasics_additionaljscode',		
		'config' => array (
			'type' => 'text',
			'cols' => '30',	
			'rows' => '5',
		)
	),
);


t3lib_div::loadTCA('pages_language_overlay');
t3lib_extMgm::addTCAcolumns('pages_language_overlay',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('pages_language_overlay','--div--;Tracking, tx_gabasics_disabletracking;;;;1-1-1, tx_gabasics_pageviewurl, tx_gabasics_donottrackpageview, tx_gabasics_additionaljscode','1');


t3lib_div::loadTCA('tt_content');
$TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';


t3lib_extMgm::addPlugin(array(
	'LLL:EXT:ga_basics/locallang_db.xml:tt_content.list_type_pi1',
	$_EXTKEY . '_pi1',
	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.png'
),'list_type');

t3lib_extMgm::addStaticFile($_EXTKEY,'static/', 'Google Analytics Basics');

?>