<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Sendeplan v2.1 for PHP-Fusion 7
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

include INFUSIONS."gr_sendeplan/infusion_db.php";
if (file_exists(INFUSIONS."gr_sendeplan/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_sendeplan/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_sendeplan/locale/German/index.php";
}

$inf_title = $locale['grsp100'];
$inf_description = $locale['grsp101'];
$inf_version = "2.1";
$inf_developer = "Ralf Thieme";
$inf_email = "scripte@granade.eu";
$inf_weburl = "http://www.granade.eu";

$inf_folder = "gr_sendeplan";

$inf_newtable[1] = DB_GR_SENDEPLAN." (
grs_id						SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
grs_user_id				SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
grs_info					VARCHAR(50) NOT NULL DEFAULT '',
grs_name					VARCHAR(20) NOT NULL DEFAULT '',
PRIMARY KEY (grs_id)
) TYPE=MYISAM;";

$inf_newtable[2] = DB_GR_SENDEPLAN_SETTINGS." (
grss_id						SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT ,
grss_sgroup				TINYINT(3) UNSIGNED NOT NULL ,
grss_ggroup				TINYINT(3) UNSIGNED NOT NULL ,
grss_agroup				TINYINT(3) UNSIGNED NOT NULL ,
grss_rhythmus			TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
grss_djon					TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
grss_djedit				TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
grss_djoff				TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
grss_replay				TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
grss_preview			TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
grss_djpic				TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
grss_autodjpic		TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
grss_week					TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
grss_offstart			TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
grss_offstop			TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
grss_offmsg				VARCHAR(200) NOT NULL DEFAULT 'Offline Nachricht',
grss_time					INT(10) UNSIGNED NOT NULL DEFAULT '0',
PRIMARY KEY (grss_id)
) TYPE=MYISAM;";

$inf_newtable[3] = DB_GR_SENDEPLAN_REPLAY." (
grsr_id						SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
grsr_re_id				SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
grsr_user_id			SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
grsr_info					VARCHAR(50) NOT NULL DEFAULT '',
grsr_name					VARCHAR(20) NOT NULL DEFAULT '',
PRIMARY KEY (grsr_id)
) TYPE=MYISAM;";

$count = '1';
while($count < 337) {
	$inf_insertdbrow[$count] = DB_GR_SENDEPLAN." VALUES('".$count."', '0', '', '')";
	$count++;
}
$inf_insertdbrow[337] = DB_GR_SENDEPLAN_SETTINGS." VALUES('1', '', '', '', '1', '1', '1', '1', '1', '0', '1', '0', '0', '0', '0', 'Offline Nachricht', '".time()."')";

$inf_droptable[1] = DB_GR_SENDEPLAN;
$inf_droptable[2] = DB_GR_SENDEPLAN_SETTINGS;
$inf_droptable[3] = DB_GR_SENDEPLAN_REPLAY;

$inf_adminpanel[1] = array(
	"title" => $locale['grsp100'],
	"image" => "",
	"panel" => "gr_sendeplan_admin.php",
	"rights" => "GRSP"
);

$inf_sitelink[1] = array(
	"title" => $locale['grsp102'],
	"url" => "../../sendeplan.php",
	"visibility" => "0"
);
?>