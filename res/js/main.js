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
(function($, undefined) {

		// wait until jQuery is defined
	try {
		var interval = window.setInterval(function() {
			var jQueryIsDefined = window.jQuery?true:false;
			if (jQueryIsDefined) {
				window.clearInterval(interval);
				
				bindTrackingEvents();
			}
		}, 50);
	} catch(err){ console.log(err); }


	function bindTrackingEvents() {

		var $ = window.jQuery;
	
			// tracks external links as events of type "Outbound Links" AND downloads as events of type "Download"
		$(document).on('click', 'a[data-gabasicstrackexternal="1"], a[data-gabasicstrackdownload="1"]', function(evt) {
			evt.preventDefault();
			var 
				url       = $(this).attr('href')
				,linkType = '';
			
			if ($(this).data('gabasicstrackexternal') == 1) {
				
					// events of type "Outbound Links" 
				linkType = 'Outbound Links';
				
			} else if ($(this).data('gabasicstrackdownload') == 1) {
				
					// events of type "Download"
				linkType = 'Download';
			}
			
			try {
				_gaq.push(['_trackEvent', 'Link', linkType, url]);
				window.setTimeout(function() {
					window.open(url);
				}, 200);
			} catch(err){ console.log(err); }
		});
			
			// sets gaq.push-link parameter so Google Analytics won't set referers from our own domains
		$(document).on('click', 'a[data-gabasicstracklink="1"]', function(evt) {
			evt.preventDefault();
			
			var url = $(this).attr('href');
			
			try {
				_gaq.push(['_link', url]);
				window.setTimeout(function() {
		//			window.location.href = url;
		//			window.open(url);
				}, 200);
			} catch(err){ console.log(err) }
		});
		
			// tracks individually added clicks to Google Analytics as Events
			// use data-gabasictrackclick="1" to register for a click and data-gabasictrackclicklabel="Label" as the label for the event
		$(document).on('click', 'a[data-gabasictrackclick="1"]', function(evt) {
			evt.preventDefault();
			var 
				url       = $(this).attr('href')
				,label    = $(this).data('gabasictrackclicklabel');
			
			if (label.length == 0) {
				label = "url";
			}
			
			try {
				_gaq.push(['_trackEvent', 'Click', label, url]);
				window.setTimeout(function() {
					window.open(url);
				}, 200);
			} catch(err){ console.log(err); }
			
		});
	
	}

})(window.jQuery)

