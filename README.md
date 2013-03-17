t3ext-ga_basics
===============

Adds basic Google Analytics features for sidewide integration of Google Analytics Tracking Code and the ability to manipulate some aspects of the tracking code on a page basis (page properties).

This extension includes the Standard Google Analytics Tracking Code within the header of your pages. There are several options that you can use to configure the way the code is generated and the configuration allows for the following kind of options and methods:

* Standard pageview tracking
* Generation of tracked URL (Canonical URL) or use of manually edited URL for pageview
* Disable the automatic pageview tracking on a page by page basis (if you need to have specific pageview tracking within plugin, e.g. /step-1, /step-2 etc.)
* Add manually input code into the JavaScript of the header on a page by page basis
* Disable the tracking for specific pages altogether (GA code will not be included on specified pages)
* Automatic tracking of links to external pages (using Google Analytics Events)
* Automatic tracking of links to files for tracking of downloads (using Google Analytics Events). Download tracking uses a black list (file extensions not to regard as downloads, e.g. jpg,png,html) and can use a white list (specific list of file extensions that will be regarded as a "download") or track every link to a file that is not ruled out by the black list of file extensions.
* Tracking of multiple subdomains using _setDomainName in Constants
* Tracking of mulitple domains using a list of domains to allow for linking between your own domains of the same profile (using _gaq.push(['_link']))
* Tracking of error pages (using Google Analytics Events instead of pageviews, the requested URL is part of the event-request)
* Use the jQuery version that is delivered with this extension or assume a jQuery lib is already included