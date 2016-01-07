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

if (!defined("DB_GR_RADIOSTATUS")) {
	define("DB_GR_RADIOSTATUS", DB_PREFIX."gr_radiostatus");
}

if (!defined("DB_GR_RADIOSTATUS_GRUSSBOX")) {
	define("DB_GR_RADIOSTATUS_GRUSSBOX", DB_PREFIX."gr_radiostatus_grussbox");
}
?>