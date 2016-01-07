<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright � 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: infusion_db.php
| CVS Version: 1.00
| Author: INSERT NAME HERE
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) {
	die("Access Denied");
}

if (!defined("DB_CHARTS")) {
	define("DB_CHARTS", DB_PREFIX."UC_charts");
}
if (!defined("DB_NEUEINTRAG")){
    define("DB_NEUEINTRAG", DB_PREFIX."UC_neueintrag");
}

if (!defined("DB_TIMECHECK")){
    define("DB_TIMECHECK", DB_PREFIX."UC_timecheck");
}
?>