<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Radiostatus v1.0 for PHP-Fusion 7
| Filename: gr_radiostatus_admin.php
| Author: Ralf Thieme
| Webseite: www.granade.eu
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

include INFUSIONS."gr_radiostatus_panel/infusion_db.php";
if (file_exists(INFUSIONS."gr_radiostatus_panel/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_radiostatus_panel/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_radiostatus_panel/locale/German/index.php";
}

$inf_title = $locale['grrs_title'];
$inf_description = $locale['grrs_desc'];
$inf_version = "1.0";
$inf_developer = "Ralf Thieme";
$inf_email = "scripte@granade.eu";
$inf_weburl = "http://www.granade.eu";
$inf_folder = "gr_radiostatus_panel";

$inf_newtable[1] = DB_GR_RADIOSTATUS." (
rs_id							SMALLINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
rs_name						VARCHAR(20) DEFAULT 'Stream' NOT NULL,
rs_ip							VARCHAR(255) DEFAULT '0.0.0.0' NOT NULL,
rs_port						VARCHAR(5) DEFAULT '8000' NOT NULL,
rs_pw							VARCHAR(255) DEFAULT '*****' NOT NULL,
rs_gb							TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
rs_ps							VARCHAR(255) DEFAULT '0' NOT NULL,
rs_tele						VARCHAR(255) DEFAULT '0' NOT NULL,
rs_servertyp			TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
rs_usertyp				TINYINT(1) UNSIGNED DEFAULT '1' NOT NULL,
rs_status					TINYINT(1) UNSIGNED DEFAULT '0' NOT NULL,
rs_order					SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
rs_gaccess				TINYINT(3) DEFAULT '102' NOT NULL,
rs_access					TINYINT(3) DEFAULT '0' NOT NULL,
PRIMARY KEY (rs_id)
) TYPE=MyISAM;";

$inf_newtable[2] = DB_GR_RADIOSTATUS_GRUSSBOX." (
rsgb_id						BIGINT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
rsgb_userip				VARCHAR(100) DEFAULT '0.0.0.0' NOT NULL,
rsgb_username			VARCHAR(100) DEFAULT 'NoName' NOT NULL,
rsgb_ort					VARCHAR(100) DEFAULT '-' NOT NULL,
rsgb_title				VARCHAR(100) DEFAULT '-' NOT NULL,
rsgb_interpreter	VARCHAR(100) DEFAULT '-' NOT NULL,
rsgb_gruss				TEXT NOT NULL,
rsgb_time					INT(10) DEFAULT '0' NOT NULL,
rsgb_status				TINYINT(1) DEFAULT '0' NOT NULL,
rsgb_stream				SMALLINT(5) DEFAULT '0' NOT NULL,
PRIMARY KEY (rsgb_id)
) TYPE=MyISAM;";

$inf_droptable[1] = DB_GR_RADIOSTATUS;
$inf_droptable[2] = DB_GR_RADIOSTATUS_GRUSSBOX;

$inf_adminpanel[1] = array(
	"title" => $locale['grrs_admin1'],
	"image" => "",
	"panel" => "gr_radiostatus_admin.php",
	"rights" => "GRRS"
);

?>