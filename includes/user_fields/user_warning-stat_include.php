<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: user_warning-stat_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if($profile_method == "input") {
    // Nothing here
} elseif ($profile_method == "display") {

	if($settings['warning_system']) {
	
	require_once LOCALE.LOCALESET."warning.php";
	require_once INCLUDES."warning.inc.php";
	
	echo "<tr>\n
		  <td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_warning-stat']."</td>\n";
	$user_points = show_warning_points($user_data['user_id']);
	echo "<td align='right' class='tbl1'>".$user_points." ".($user_points==1 ? $locale['WARN201'] : $locale['WARN202'])." ".
	warning_profile_link("1", $user_data['user_id'], $user_points)."</td>\n
		  </tr>\n";
	}

} elseif ($profile_method == "validate_insert") {
	// Nothing here
} elseif ($profile_method == "validate_update") {
	// Nothing here
}
?>
