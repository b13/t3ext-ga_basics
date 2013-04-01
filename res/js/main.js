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



		// bind tracking events
		// if the link url should not be change your window location add a data-gabasiscupdateurl="false" to the <a>-tag
	function bindTrackingEvents() {

		var $ = window.jQuery;

			// tracks external links as events of type "Outbound Links" AND downloads as events of type "Download"
		$(document).on('click', 'a[data-gabasicstrackexternal="1"], a[data-gabasicstrackdownload="1"]', function() {
/* 			evt.preventDefault(); */

			var 
				$this     = $(this)
				,linkType = '';

			if ($this.data('gabasicstrackexternal') == 1) {

					// events of type "Outbound Links" 
				linkType = 'Outbound Links';

			} else if ($this.data('gabasicstrackdownload') == 1) {

					// events of type "Download"
				linkType = 'Download';
			}

			var 
				url	        = $this.attr('href')
				,linkAction = $this.attr('target') === "_blank" ? 'newWindow' : 'update'
				,pushMsg    = ['_trackEvent', 'Link', linkType, url];

			if ($this.data('gabasiscupdateurl') == false) { linkAction = ''; }

			doTracking(pushMsg, url, linkAction);
			
		});


			// tracks individually added clicks to Google Analytics as Events
			// use data-gabasicstrackclick="1" to register for a click and data-gabasicstrackclicklabel="Label" as the label for the event
		$(document).on('click', 'a[data-gabasicstrackclick="1"]', function(evt) {			
			evt.preventDefault();
			var 
				$this       = $(this)
				,url	    = $this.attr('href')
				,linkAction = $this.attr('target') === "_blank" ? 'newWindow' : 'update'
				,label      = $this.data('gabasicstrackclicklabel') ? $(this).data('gabasicstrackclicklabel') : url
				,pushMsg    = ['_trackEvent', 'Click', label, url];

			if ($this.data('gabasiscupdateurl') == false) { linkAction = ''; }

			doTracking(pushMsg, url, linkAction);
		});


			// sets gaq.push-link parameter so Google Analytics won't set referers from our own domains
		$(document).on('click', 'a[data-gabasicstracklink="1"]', function(evt) {
			evt.preventDefault();
			var 
				$this       = $(this)
				,url	    = $this.attr('href')
				,linkAction = $this.attr('target') === "_blank" ? 'newWindow' : 'update'
				,pushMsg    = ['_link', url];
				
			if ($this.data('gabasiscupdateurl') == false) { linkAction = ''; }

			doTracking(pushMsg, url, linkAction);
		});
	}
	
		// push the msg to GA and fire the link action
		// @linkAction: can be "newWindow" to open the link in a new Window, "update" to update the url or "" to do nothing
	function doTracking(pushMsg, url, linkAction) {

		try {
			_gaq.push(pushMsg, function() {
				
				window.setTimeout(function() {
					switch (linkAction) {
						case 'newWindow':
							window.open(url);
						break;
						case 'update':
							window.location.href = url;
						break;
						default:
							// do not update url
					}
				}, 300);
			
			});
		} catch(err){ console.log(err); }
	}

})(window.jQuery);