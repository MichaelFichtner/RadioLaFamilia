<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: online_users_panel.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }
	
openside($locale['global_010']);
pif_cache("online_users");
echo THEME_BULLET." ".$locale['global_011'].": ".$pif_cache['online_users']['guests']."<br /><br />\n";
echo THEME_BULLET." ".$locale['global_012'].": ".count($pif_cache['online_users']['members'])."<br />\n";
if (count($pif_cache['online_users']['members'])) {
	$i = 1;
	while (list($key, $member) = each($pif_cache['online_users']['members'])) {
		echo "<span class='side'>".profile_link($member['user_id'], $member['user_name'], $member['user_status'])."</span>";
		if ($i != count($pif_cache['online_users']['members'])) { echo ",\n"; } else { echo "<br />\n"; }
		$i++;
	}
}
pif_cache("total_reg_users");
echo "<br />\n".THEME_BULLET." ".$locale['global_014'].": ".$pif_cache['total_reg_users']."<br />\n";
if (iADMIN && checkrights("M") && $settings['admin_activation'] == "1") {
	echo THEME_BULLET." <a href='".ADMIN."members.php".$aidlink."&amp;status=2' class='side'>".$locale['global_015']."</a>";
	echo ": ".dbcount("(user_id)", DB_USERS, "user_status='2'")."<br />\n";
}
pif_cache("newest_reg_member");
echo THEME_BULLET." ".$locale['global_016'].": <span class='side'>";
echo profile_link($pif_cache['newest_reg_member']['user_id'], $pif_cache['newest_reg_member']['user_name'], $pif_cache['newest_reg_member']['user_status']);
echo "</span>\n";
closeside();
?>