<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: user_forumpanellocale_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Author: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
	if (true) {
		echo "<tr>\n";
		echo "<td class='tbl'>".$locale['uf_forum_panel']."</td>\n";
		echo "<td class='tbl'><select name='user_forumpanellocale' class='textbox' style='width:100px;'>\n".make_admin_language_opts((isset($user_data['user_forumpanellocale']) ? $user_data['user_forumpanellocale'] : ""))."\n</select></td>\n";
		echo "</tr>\n";
	}
} elseif ($profile_method == "display") {
	// Not shown in profile
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_forumpanellocale";
	$db_values .= ", '".(isset($_POST['user_forumpanellocale']) ? stripinput(trim($_POST['user_forumpanellocale'])) : "all")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_forumpanellocale='".(isset($_POST['user_forumpanellocale']) ? stripinput(trim($_POST['user_forumpanellocale'])) : "all")."'";
}
?>