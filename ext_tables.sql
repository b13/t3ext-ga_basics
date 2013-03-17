#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_gabasics_disabletracking tinyint(3) DEFAULT '0' NOT NULL,
	tx_gabasics_pageviewurl tinytext,
	tx_gabasics_donottrackpageview tinyint(3) DEFAULT '0' NOT NULL,
	tx_gabasics_additionaljscode text
);


#
# Table structure for table 'pages_language_overlay'
#
CREATE TABLE pages_language_overlay (
	tx_gabasics_disabletracking tinyint(3) DEFAULT '0' NOT NULL,
	tx_gabasics_pageviewurl tinytext,
	tx_gabasics_donottrackpageview tinyint(3) DEFAULT '0' NOT NULL,
	tx_gabasics_additionaljscode text
);