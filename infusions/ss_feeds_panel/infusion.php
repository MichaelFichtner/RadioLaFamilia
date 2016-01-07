<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: infusion.php
| Author: SiteMaster, slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

include INFUSIONS."ss_feeds_panel/infusion_db.php";

if (file_exists(INFUSIONS."ss_feeds_panel/locale/".LOCALESET."infusion.php")) {
	include INFUSIONS."ss_feeds_panel/locale/".LOCALESET."infusion.php";
} else {
	include INFUSIONS."ss_feeds_panel/locale/English/infusion.php";
}

// Infusion general information
$inf_title = $locale['ssfp_title'];
$inf_description = $locale['ssfp_desc'];
$inf_version = "1.3 PiF";
$inf_developer = "SiteMaster & slaughter";
$inf_email = "";
$inf_weburl = "http://www.sitemaster.dk";

$inf_folder = "ss_feeds_panel"; // The folder in which the infusion resides.

// Delete any items not required below.
$inf_newtable[1] = DB_SS_FEEDS." (
	feed_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
	feed_name VARCHAR(50) NOT NULL,
	feed_icon VARCHAR(50) NOT NULL,
	feed_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
	feed_updfrq SMALLINT(2) UNSIGNED NOT NULL DEFAULT '24',
	PRIMARY KEY (feed_id),
	KEY feed_order (feed_order)
) TYPE=MyISAM;";

$inf_insertdbrow[1] = DB_SS_FEEDS." (feed_name, feed_order) VALUES('news_feed', '1')";
$inf_insertdbrow[2] = DB_PANELS." SET panel_name='".$locale['ssfp_title']."', panel_filename='".$inf_folder."', panel_content='', panel_side='1', panel_order='2', panel_type='file', panel_access='0', panel_display='0', panel_status='1' ";

$inf_droptable[1] = DB_SS_FEEDS;

$inf_deldbrow[1] = DB_PANELS." WHERE panel_filename='".$inf_folder."'";

$inf_adminpanel[1] = array(
	"title" => $locale['ssfp_admin'],
	"image" => "rss.png",
	"panel" => "ss_feeds_admin.php",
	"rights" => "SSFP"
);
?>