<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2013 David Steeb <typo3@b13.de>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/** 
 * @author		David Steeb (typo3@b13.de) 
 * @subpackage	tx_gabasics
 * 
 * This package includes all hook implementations.
 */

class tx_gabasics {

	/**
	 * parameter to add
	 *
	 * @var string
	 */
	protected $applicableParameters = NULL;

	/**
	 * the key of the extension
	 *
	 * @var string
	 */
	protected $extensionKey = 'ga_basics';

	/**
	 * the configuration of the extension
	 *
	 * @var array
	 */
	protected $configuration = array();

	/**
	 * enable Cross Domain Tracking
	 *
	 * @var boolean
	 */
	protected $crossDomainTracking = FALSE;

	/**
	 * domains for Cross Domain Tracking
	 *
	 * @var array
	 */
	protected $linkDomains = array();
	
	/**
	 * include subdomains in Cross Domain Tracking for all domains given
	 *
	 * @var boolean
	 */
	protected $includeSubdomains = FALSE;
	
	/**
	 * enable Download Tracking
	 *
	 * @var boolean
	 */
	protected $downloadTracking = FALSE;

	/**
	 * file extension list for Download Tracking
	 *
	 * @var array
	 */
	protected $fileExtensions = array();

	/**
	 * list of file extensions that we need to ignore when tracking downloads
	 *
	 * @var array
	 */
	protected $fileExtensionsToIgnore = array();
	
	/**
	 * enable External Link Tracking
	 *
	 * @var boolean
	 */
	protected $externalLinkTracking = FALSE;
	
	/**
	 * Constructor
	 *
	 * @return void
	 */
	public function __construct() {

		// Remind: this class is a singleton so attributes need to be initialized only once
		if (empty($this->configuration)) {
			$this->configuration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf'][$this->extensionKey]);
			$this->configuration = $GLOBALS['TSFE']->tmpl->setup['plugin.']['tx_gabasics.']['settings.'];
			
				// enable Cross Domain Tracking
			$this->crossDomainTracking = $this->configuration['crossdomaintracking'];
			if ($this->crossDomainTracking) {
				$this->linkDomains = explode(',', $this->configuration['crossdomain_domainlist']);
				$this->linkDomains = array_map('trim', $this->linkDomains);
				$this->includeSubdomains = $this->configuration['crossdomain_includesubdomains'];
			}
			
				// enable Download Tracking
			$this->downloadTracking = $this->configuration['downloadtracking'];
			if ($this->downloadTracking) {
				$this->fileExtensions = explode(',', $this->configuration['download_extlist']);
				$this->fileExtensions = array_map('trim', $this->fileExtensions);
				$this->fileExtensionsToIgnore = explode(',', $this->configuration['download_extlisttoignore']);
			}
			
				// enable External Link Tracking
			$this->externalLinkTracking = $this->configuration['externallinktracking'];
		}
	}


	/**
	 * Process all links to add additional classes to Cross-Domain-Links, Download-Links, External Links
	 *
	 * @param array $parameters: Array of parameters from typoLink_PostProc hook in tslib_content
	 * @param object $cObj: Reference to the calling tslib_content instance
	 * @return void
	 */
	public function processAllLinks (&$parameters, &$pObj) {

			// external Link Tracking
		if ($this->externalLinkTracking || $this->crossDomainTracking) {
				// track external URL
			if (isset($parameters['finalTagParts']['TYPE']) && $parameters['finalTagParts']['TYPE'] == 'url') {
				if (stristr($parameters['finalTag'], 'data-gabasicsnotracking="1"')) {
					// do not change anything
				} 
				else {
					$trackCrossDomain = FALSE;
					// check to see if we need to consider Cross Domain Tracking
					if ($this->crossDomainTracking && $this->linkDomains) {
						foreach ($this->linkDomains as $checkdomain) {
							if ($this->includeSubdomains) {
								// this registers in a link like b13.de/index.php or like "b13.de"
								if (stristr($parameters['finalTag'], '' . $checkdomain . '/') || 
									stristr($parameters['finalTag'], '' . $checkdomain . '"')) {
										$trackCrossDomain = TRUE;
								}
							} else {
								// this registers in a link like b13.de/index.php
								if (stristr($parameters['finalTag'], '//' . $checkdomain . '/') ||
									stristr($parameters['finalTag'], '//' . $checkdomain . '"')) {
										$trackCrossDomain = TRUE;
								}
							}
						}
					}
					if ($trackCrossDomain) {
						// add Cross Domain Link Parameter
						if (!stristr($parameters['finalTag'], 'data-gabasicstracklink')) {
							// add data tag to the final Tag output and the ATagParams
							$parameters['finalTag'] = str_replace('>', ' data-gabasicstracklink="1">', $parameters['finalTag']);
							$parameters['finalTagParts']['aTagParams'] .= ' data-gabasicstracklink="1"';
						}
					} else if ($this->externalLinkTracking) {
						// do the standard or External Link Tracking
						if (!stristr($parameters['finalTag'], 'data-gabasicstrackexternal')) {
							// add data tag to the final Tag output and the ATagParams
							$parameters['finalTag'] = str_replace('>', ' data-gabasicstrackexternal="1">', $parameters['finalTag']);
							$parameters['finalTagParts']['aTagParams'] .= ' data-gabasicstrackexternal="1"';
						}
					}
				}
				
				return;
			}
		}
		
			// track File Downloads
		if ($this->downloadTracking) {
			if (isset($parameters['finalTagParts']['TYPE']) && $parameters['finalTagParts']['TYPE'] == 'file') {
				if (stristr($parameters['finalTag'], 'data-gabasicsnotracking="1"')) {
					// do not change anything
				} else if (!stristr($parameters['finalTag'], 'data-gabasicstrackdownload')) {
						// if the file has a file extension we want to skip, do not mark the link
					if ($this->fileExtensionsToIgnore) {
						foreach($this->fileExtensionsToIgnore as $extension) {
							if (stristr($parameters['finalTag'], '.' . $extension)) {
								return;
							}
						}
					}
						// if the file has not been skipped check to see if the file extension is on the white list
					if ($this->fileExtensions) {
						foreach($this->fileExtensions as $extension) {
							if (stristr($parameters['finalTag'], '.' . $extension)) {
								// add data tag to the final Tag output and the ATagParams
								$parameters['finalTag'] = str_replace('>', ' data-gabasicstrackdownload="1">', $parameters['finalTag']);
								$parameters['finalTagParts']['aTagParams'] .= ' data-gabasicstrackdownload="1">';
								return;
							}
						}
						return;
					}
						// add data tag to the final Tag output and the ATagParams if there is no white list
					$parameters['finalTag'] = str_replace('>', ' data-gabasicstrackdownload="1">', $parameters['finalTag']);
					$parameters['finalTagParts']['aTagParams'] .= ' data-gabasicstrackdownload="1">';
				}
				return;
			}
		}
	}

	/**
	 * User Function that returns the Header Code for
	 * a) Cross Domain Tracking, if the currently used domain is in the list of domains to link
	 * b) setDomainName, if there is no Cross Domain Tracking Domainlist but setdomainname has a value
	 *
	 * @param array $content
	 * @param array $conf
	 * @return string
	 */

	public function getDomainTrackingCode($content, $conf) {
		$returnHeaderCode = "";
			// build the header code for Cross Domain Tracking
		if ($this->crossDomainTracking && $this->linkDomains) {
				// the domain that is being used in the current page
			$currentDomain = t3lib_div::getIndpEnv('HTTP_HOST');
				// the domain we will give in the _setDomainName header code
			$domainName = "";
			foreach ($this->linkDomains as $checkdomain) {
				if (stristr($currentDomain, $checkdomain)) {
					$domainName = $checkdomain;
					continue;
				}
			}
			if ($domainName) {
				$returnHeaderCode = "_gaq.push(['_setDomainName', '" . $domainName ."']);\r\n	";
				$returnHeaderCode .= "_gaq.push(['_setAllowLinker', true]);\r\n	";
			}
				// return the header code if not still empty
			if ($returnHeaderCode) return $returnHeaderCode;
		}
			// if there is no headercode based on Cross Domain Tracking and the domain list given
			// use the setdomain instead (for simple domain/subdomain-tracking)
		if ($this->configuration['setdomainname']) {
			return "_gaq.push(['_setDomainName', '" . $this->configuration['setdomainname'] . "']);\r\n	";
		}
	}

	/**
	 * User Function that creates the "ga('create')"-Code for inclusion in the head of the page
	 * This function returns
	 *   ga('require', 'linker');
	 *   ga('linker:autoLink', ['domain1', 'domain2']);
	 *   ga('create', 'UA-XXX', 'auto', {
	 *     'allowLinker': true,
	 *     'siteSpeedSampleRate': 50
	 *   });
	 * with
	 *   - allowLinker set if cross-domain-tracking is required
	 *   - siteSpeedSampleRate set as defined in constants
	 *
	 * @param array $content
	 * @param array $conf
	 * @return string
	 */

	public function getTrackingConfigurationCode($content, $conf) {

		$returnHeaderCode = "";

			// build the header code for Cross Domain Tracking
		if ($this->crossDomainTracking && $this->linkDomains) {
				// the domain that is being used in the current page
			$currentDomain = t3lib_div::getIndpEnv('HTTP_HOST');
				// the domain we will give in the _setDomainName header code
			$domainName = "";
			foreach ($this->linkDomains as $checkdomain) {
				if (stristr($currentDomain, $checkdomain)) {
					$domainName = $checkdomain;
					continue;
				}
			}
			if ($domainName) {
				$returnHeaderCode  = "ga('require', 'linker');\r\n	";
				$returnHeaderCode .= "ga('linker:autoLink', ['" . implode("', '", $this->linkDomains) . "']);\r\n	";
				$returnHeaderCode .= "ga('create', '" . $this->configuration['ua-no'] . "', 'auto', {\r\n		"
				. "'allowLinker': true,\r\n		"
				. "'siteSpeedSampleRate': " . $this->configuration['sitespeedsample'] . "\r\n	"
				. "});\r\n	";
			}

		} else {
			$returnHeaderCode = "ga('create', '" . $this->configuration['ua-no'] . "', 'auto', {\r\n		"
			. "'siteSpeedSampleRate': " . $this->configuration['sitespeedsample'] . "});\r\n	";
		}
			// return the header code if not still empty
		if ($returnHeaderCode) return $returnHeaderCode;
	}

}


if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ga_basics/class.tx_gabasics.php']) {
   include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/ga_basics/class.tx_gabasics.php']);
}
?>
