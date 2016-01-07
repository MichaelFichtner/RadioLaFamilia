<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: suspended_panel.php
| Version: Pimped Fusion v0.06.00
+----------------------------------------------------------------------------+
| Authors: keddy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if (file_exists(INFUSIONS."suspended_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."suspended_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."suspended_panel/locale/English.php";
}


$result = dbquery(
	"SELECT u.user_id, u.user_name, u.user_actiontime, s.suspend_reason FROM ".DB_USERS." u
	LEFT JOIN ".DB_SUSPENDS." s ON u.user_id = s.suspended_user
	WHERE u.user_status='3'"
);
if (dbrows($result)) {
openside($locale['banned_100']); 
     while ($data = dbarray($result)) {
		
		echo "<div style='cursor: pointer' title='".$data['suspend_reason']."'>".$data['user_name']." ".$locale['banned_101'].date("d.m.Y, H:m", $data['user_actiontime'])."</div>\n";
     }
closeside();

}
?>