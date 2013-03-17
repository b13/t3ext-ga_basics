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
 * This package includes the necessary JS functions based on jQuery
 */


	// tracks external links as events of type "Outbound Links" 
$(document).on('click', 'a[data-gabasicstrackexternal="1"]', function(evt) {
	evt.preventDefault(); 
	var url = $(this).attr('href');
	try {
		_gaq.push(['_trackEvent', 'Link', 'Outbound Links', $(this).attr('href')]);
		window.setTimeout(function() {
			window.open(url);
		}, 200);
	} catch(err){}
});


	// tracks downloads as events of type "Download"
$(document).on('click', 'a[data-gabasicstrackdownload="1"]', function(evt) {
	evt.preventDefault();
	var url = $(this).attr('href');
	try {
		_gaq.push(['_trackEvent', 'Link', 'Download', $(this).attr('href')]);
		window.setTimeout(function() {
			window.open(url);
		}, 200);
	} catch(err){}
});


	// sets gaq.push-link parameter so Google Analytics won't set referers from our own domains
$(document).on('click', 'a[data-gabasicstracklink="1"]', function(evt) {
	evt.preventDefault();
	var url = $(this).attr('href');
	try {
		_gaq.push(['_link', $(this).attr('href')]);
		window.setTimeout(function() {
//			window.location.href = url;
//			window.open(url);
		}, 200);
	} catch(err){}
});


