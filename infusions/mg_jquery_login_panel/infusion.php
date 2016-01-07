<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion.php
| Author: MarcusG
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."mg_jquery_login_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."mg_jquery_login_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."mg_jquery_login_panel/locale/German.php";
}

// Infusion general information
$inf_title = $locale['JQL_title'];
$inf_description = $locale['JQL_desc'];
$inf_version = $locale['JQL_version'];
$inf_developer = "MarcusG";
$inf_email = "";
$inf_weburl = "http://phpfusion.marcusg.de";

$inf_folder = "mg_jquery_login_panel"; // The folder in which the infusion resides.


$inf_insertdbrow[1] = DB_PANELS." SET panel_name='".$locale['JQL_title']."', panel_filename='".$inf_folder."', panel_side=2, panel_order='1', panel_type='file', panel_access='0', panel_display='1', panel_status='1' ";

$inf_deldbrow[1] = DB_PANELS." WHERE panel_filename='".$inf_folder."'";
?>