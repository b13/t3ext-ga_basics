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
		//
		// if the link we want to track opens with target="_blank" we let the browser handle the link click and just add
		// a tracking function to the link. For all other targets that might be external links or change the content of
		// our browser window that does the tracking and runs this JS we need to prevent the link from opening the href
		// right away, do the tracking, wait and refresh the url - configured using linkAction="update"
	function bindTrackingEvents() {

		var $ = window.jQuery;


		// tracks external links as events of type "Outbound Links"
		$(document).on('click', 'a[data-gabasicstrackexternal="1"], a[data-gabasicstrackdownload="1"], a[data-gabasicstrackclick="1"]', function(evt) {

			var	$this       = $(this)
				,linkType    = 'Default Event';

			// track external links
			if ($this.data('gabasicstrackexternal') == 1) {
				var linkType = 'Link';
			}
			// track downloaded files
			if ($this.data('gabasicstrackdownload') == 1) {
				var linkType = 'Download';
			}
			// track clicks
			if ($this.data('gabasicstrackclick') == 1) {
				var linkType = 'Click';
			}

			var
				url	    = $this.attr('href')
				,linkAction = $this.attr('target') === "_blank" ? '' : 'update'
				,label      = $this.data('gabasicstrackclicklabel') ? $(this).data('gabasicstrackclicklabel') : ( $this.attr('title') ? $(this).attr('title') : url )
				,pushMsg    = ['_trackEvent', linkType, label, url];

			if ($this.data('gabasiscupdateurl') == false) { linkAction = ''; }
			if (linkAction == 'update') { evt.preventDefault(); }

			doTracking(pushMsg, url, linkAction);

		});


			// for multi-domain tracking
			// sets gaq.push-link parameter so Google Analytics won't set referers from our own domains
		$(document).on('click', 'a[data-gabasicstracklink="1"]', function(evt) {

			var
				$this       = $(this)
				,url	    = $this.attr('href')
				,linkAction = $this.attr('target') === "_blank" ? '' : 'update'
				,pushMsg    = ['_link', url];
				
			if ($this.data('gabasiscupdateurl') == false) { linkAction = ''; }
			if (linkAction == 'update') { evt.preventDefault(); }

			doTracking(pushMsg, url, linkAction);
		});

	}
	
		// push the msg to GA and fire the link action
		// @linkAction: can be "newWindow" to open the link in a new Window, "update" to update the url or "" to do nothing
	function doTracking(pushMsg, url, linkAction) {

		try {
			_gaq.push(pushMsg, function() {

				// handle the reload if the linkAction is set to "update" (this means we used evt.preventDefault() before
				// and need to handle the link ourselves
				if (linkAction == 'update') {
					window.setTimeout(function() {
						window.location.href = url;
					}, 300);
				}

			});
		} catch(err){ console.log(err); }
	}

})(window.jQuery);