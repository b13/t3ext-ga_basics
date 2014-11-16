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


// t3lib_div::loadTCA('tt_content');
// $TCA['tt_content']['types']['list']['subtypes_excludelist'][$_EXTKEY.'_pi1']='layout,select_key,pages';


// t3lib_extMgm::addPlugin(array(
// 	'LLL:EXT:ga_basics/locallang_db.xml:tt_content.list_type_pi1',
// 	$_EXTKEY . '_pi1',
// 	t3lib_extMgm::extRelPath($_EXTKEY) . 'ext_icon.png'
// ),'list_type');

t3lib_extMgm::addStaticFile($_EXTKEY,'static/', 'Google Analytics Basics');


/**
 * add a content element for custom event tracking
 *
 * this element allows for easy manipulation of event tracking data for pageviews. An event can be triggered by viewing
 * the page a content element is included in, and given the extensive access permissions and restrictions that can be set
 * for each element different events can be fired depending on for example the login status or the usergroup of a
 * frontend user.
 * In most cases you will fire events in your extension templates or using typoscript but for some use cases like
 * a "Thank you" page for a contact form that needs to be editable for simple editors and might even move or switch to
 * another pid without the integrator of the website knowing about it (redirect to pid based on flexform settings set
 * by an editor) this content element gives an easy to understand way to fire an event such as "contact form send".
 *
 * an event as of this content element consists of the following values that are saved using fields from tt_content:
 * ga('send', 'event', 'category', 'action', 'label', 'value', {'nonInteraction': 1})
 * category			header
 * action			subheader
 * label			bodytext
 * value			titleText
 * nonInteraction	imageborder
 *
 * to re-label and re-configure the labels for the used form elements we rely on pagetsconfig extension from
 * Benni Mack to allow individual re-configuration using pageTSConfig based on CType
 */

// add a tab "Google Analytics Tracking" to the Content Element Type Dropdown
$TCA['tt_content']['columns']['CType']['config']['items'][] = array(
	0 => 'LLL:EXT:ga_basics/locallang_db.xml:tt_content.CType.tab',
	1 => '--div--'
);

// add the content element
$TCA['tt_content']['columns']['CType']['config']['items'][] = array('LLL:EXT:ga_basics/locallang_db.xml:tt_content.CType.gabasics_trackevent', 'gabasics_trackevent', 'EXT:ga_basics/ext_icon.gif');

$TCA['tt_content']['types']['gabasics_trackevent']['showitem'] = '
--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.general;general,
header, subheader, bodytext, titleText, imageborder,
--div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access,
--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.visibility;visibility,
--palette--;LLL:EXT:cms/locallang_ttc.xlf:palette.access;access
';

// add the content element for event tracking to the plugins part of the new content element wizard
TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig('
		# add Elements
	mod.wizards.newContentElement.wizardItems.plugins {
		show := addToList(gabasics_trackevent)
		elements.gabasics_trackevent {
			icon = ../typo3conf/ext/ga_basics/ext_icon.gif
			title = LLL:EXT:ga_basics/locallang_db.xml:tt_content.CType.gabasics_trackevent
			description = LLL:EXT:ga_basics/locallang_db.xml:tt_content.CType.gabasics_trackevent.description
			tt_content_defValues.CType = gabasics_trackevent
			tt_content_defValues.imageborder = 1
		}
	}
	TCEFORM.tt_content {
		header.types.gabasics_trackevent.label = LLL:EXT:ga_basics/locallang_db.xml:tt_content.CType.gabasics_trackevent.header.label
		subheader.types.gabasics_trackevent.label = LLL:EXT:ga_basics/locallang_db.xml:tt_content.CType.gabasics_trackevent.subheader.label
		bodytext.types.gabasics_trackevent.label = LLL:EXT:ga_basics/locallang_db.xml:tt_content.CType.gabasics_trackevent.bodytext.label
		titleText.types.gabasics_trackevent.label = LLL:EXT:ga_basics/locallang_db.xml:tt_content.CType.gabasics_trackevent.titleText.label
		imageborder.types.gabasics_trackevent.label = LLL:EXT:ga_basics/locallang_db.xml:tt_content.CType.gabasics_trackevent.imageborder.label
		header.types.gabasics_trackevent.config.size = 20
		subheader.types.gabasics_trackevent.config.size = 20
		bodytext.types.gabasics_trackevent.config.form_type = input
		bodytext.types.gabasics_trackevent.config.size = 20
		titleText.types.gabasics_trackevent.config.form_type = input
		titleText.types.gabasics_trackevent.config.size = 5
		titleText.types.gabasics_trackevent.config.eval = int
# remove once we only use TYPO3 6.2+
		bodytext.types.gabasics_trackevent.config.rows = 1
		bodytext.types.gabasics_trackevent.config.cols = 20
		titleText.types.gabasics_trackevent.config.rows = 1
		titleText.types.gabasics_trackevent.config.cols = 5
# @todo: verify that imagewidth ranges work with 6.2
	}
');

?>