<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Teamlist v2.2 for PHP-Fusion 7
| Filename: infusion.php
| Author: Ralf Thieme (Gr@n@dE)
| HP: www.granade.eu
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

include INFUSIONS."gr_teamlist/infusion_db.php";
if (file_exists(INFUSIONS."gr_teamlist/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_teamlist/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_teamlist/locale/German/index.php";
}

$inf_title = $locale['grtl100'];
$inf_description = $locale['grtl101'];
$inf_version = "2.2";
$inf_developer = "Ralf Thieme";
$inf_email = "scripte@granade.eu";
$inf_weburl = "http://www.granade.eu";

$inf_folder = "gr_teamlist";

$inf_newtable[1] = DB_GR_TEAMLIST_GROUP." (
tlg_id						SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
tlg_name					VARCHAR(100) DEFAULT 'Group' NOT NULL,
tlg_pic						VARCHAR(50) DEFAULT '' NOT NULL,
tlg_position			SMALLINT(5) DEFAULT '1' NOT NULL,
tlg_status				TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY (tlg_id)
) TYPE=MyISAM;";

$inf_newtable[2] = DB_GR_TEAMLIST_USERS." (
tlu_id						SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
tlu_userid				SMALLINT(5) DEFAULT '1' NOT NULL,
tlu_pic						VARCHAR(50) DEFAULT '' NOT NULL,
tlu_rname					VARCHAR(50) DEFAULT '' NOT NULL,
tlu_abteil				VARCHAR(50) DEFAULT '' NOT NULL,
tlu_feld1					TEXT DEFAULT '' NOT NULL,
tlu_feld2					TEXT DEFAULT '' NOT NULL,
tlu_status				SMALLINT(5) DEFAULT '0' NOT NULL,
tlu_groups				SMALLINT(5) DEFAULT '0' NOT NULL,
tlu_position			SMALLINT(5) DEFAULT '0' NOT NULL,
PRIMARY KEY (tlu_id)
) TYPE=MyISAM;";

$inf_droptable[1] = DB_GR_TEAMLIST_GROUP;
$inf_droptable[2] = DB_GR_TEAMLIST_USERS;

$inf_adminpanel[1] = array(
	"title" => $locale['grtl100'],
	"image" => "",
	"panel" => "gr_teamlist_admin.php",
	"rights" => "GRTL"
);

$inf_sitelink[1] = array(
	"title" => $locale['grtl102'],
	"url" => "../../teamlist.php",
	"visibility" => "0"
);
?>