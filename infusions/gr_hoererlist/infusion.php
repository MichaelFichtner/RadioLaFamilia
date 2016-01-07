<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Hörerlist v2.2 for PHP-Fusion 7
| Filename: infusion.php
| Author: Ralf Thieme (Gr@n@dE)
| Homepage: www.granade.eu
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

include INFUSIONS."gr_hoererlist/infusion_db.php";
if (file_exists(INFUSIONS."gr_hoererlist/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_hoererlist/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_hoererlist/locale/German/index.php";
}

$inf_title = $locale['grhl100'];
$inf_description = $locale['grhl101'];
$inf_version = "2.2";
$inf_developer = "Ralf Thieme";
$inf_email = "scriptee@granade.eu";
$inf_weburl = "http://www.granade.eu";

$inf_folder = "gr_hoererlist";

$inf_newtable[1] = DB_GR_HOERERLIST." (
hl_id					SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
hl_user_id    SMALLINT(5) DEFAULT '1' NOT NULL,
hl_free		    TINYINT(5) DEFAULT '0' NOT NULL,
PRIMARY KEY (hl_id)
) TYPE=MyISAM;";

$inf_droptable[1] = DB_GR_HOERERLIST;

$inf_adminpanel[1] = array(
	"title" => $locale['grhl100'],
	"image" => "",
	"panel" => "gr_hoererlist_admin.php",
	"rights" => "GRHL"
);

$inf_sitelink[1] = array(
	"title" => $locale['grhl102'],
	"url" => "../../hoererlist.php",
	"visibility" => "0"
);

?>