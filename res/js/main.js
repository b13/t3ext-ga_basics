/***************************************************************
*  Copyright notice
*  
*  (c) 2013,2014 David Steeb <typo3@b13.de>
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

;(function(factory) {

	if (typeof define === 'function' && define.amd) {

        define('ga_basics', ['jquery'], function($) {
			factory();
		});

	} else {

		var errorCount = 0;

			// wait until jQuery is defined
		try {
			var interval = window.setInterval(function() {
				var jQueryIsDefined = window.jQuery ? true : false;

				if (jQueryIsDefined) {
					window.clearInterval(interval);
					factory();

				} else {

					if (errorCount > 100) {
						throw "no jquery"

					} else {
						errorCount += 1;

					}
				}
			}, 50);
		} catch(err) { console.log(err); }

    }

})(function() {

	var me = this;

	function initialize() {
		events();
		return me;
	}

	function events() {

		// bind tracking events
		// if the link url should not be change your window location add a data-gabasiscupdateurl="false" to the <a>-tag
		//
		// if the link we want to track opens with target="_blank" we let the browser handle the link click and just add
		// a tracking function to the link. For all other targets that might be external links or change the content of
		// our browser window that does the tracking and runs this JS we need to prevent the link from opening the href
		// right away, do the tracking, wait and refresh the url - configured using linkAction="update"


			// tracks external links as events of type "Outbound Links"
		$(document).on('click', 'a[data-gabasicstrackexternal="1"], a[data-gabasicstrackdownload="1"], a[data-gabasicstrackclick="1"]', function(evt) {

			var
				$this           = $(this)
				, eventCategory = 'Default Event';

				// track external links
			if ($this.data('gabasicstrackexternal') == 1) {
				eventCategory = 'Outbound Link';
			}
				// track downloaded files
			if ($this.data('gabasicstrackdownload') == 1) {
				eventCategory = 'Download';
			}
				// track clicks
			if ($this.data('gabasicstrackclick') == 1) {
				eventCategory = $this.data('gabasicseventcategory') ? $(this).data('gabasicseventcategory') : 'Click';
			}

			var
				url	          = $this.attr('href')
				, linkAction  = $this.attr('target') === "_blank" ? '' : 'update'
				, eventAction = $this.data('gabasicseventaction') ? $(this).data('gabasicseventaction') : ( $this.attr('title') ? $(this).attr('title') : url )
				, eventLabel  = $this.data('gabasicseventlabel')  ? $(this).data('gabasicseventlabel')  : ( $this.attr('alt')   ? $(this).attr('alt')   : url );

			if ($this.data('gabasiscupdateurl') == false) { linkAction = ''; }
			if (linkAction == 'update') { evt.preventDefault(); }
			
				// fire the event tracking
			try {
				ga('send', {
					'hitType': 'event',
					'eventCategory': eventCategory,
					'eventAction': eventAction,
					'eventLabel': eventLabel,
					'hitCallback': function () {
						// handle the reload if the linkAction is set to "update" (this means we used evt.preventDefault() before
						// and need to handle the link ourselves
						if (linkAction == 'update') {
							window.setTimeout(function () {
								window.location.href = url;
							}, 300);
						}
					}
				});
			} catch(err) { console.log(err); }
		});

		// TODO: add event for multi-domain tracking
	}

	return initialize();
});