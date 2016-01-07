<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: user_gendertext_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Author: gh0st2k
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
	echo "<tr>\n";
	echo "<td class='tbl'>".$locale['uf_gendertext_name']."</td>\n";
	echo "<td class='tbl'>";
	echo "<input type='radio' name='user_gendertext' value='0' ".(isset($user_data['user_gendertext']) && $user_data['user_gendertext'] == 0 ? "checked='checked'" : "")." /> ".$locale['uf_gendertext_no_data'];
	echo " <input type='radio' name='user_gendertext' value='1' ".(isset($user_data['user_gendertext']) && $user_data['user_gendertext'] == 1 ? "checked='checked'" : "")." /> ".$locale['uf_gendertext_female'];
	echo " <input type='radio' name='user_gendertext' value='2' ".(isset($user_data['user_gendertext']) && $user_data['user_gendertext'] == 2 ? "checked='checked'" : "")." /> ".$locale['uf_gendertext_male'];
	echo "</td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	if ($user_data['user_gendertext']) {
		echo "<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_gendertext_name']."</td>\n";
		echo "<td align='right' class='tbl1'>";
		if (isset($user_data['user_gendertext'])) {
			if ($user_data['user_gendertext'] == 1) { echo $locale['uf_gendertext_female']; }
			elseif ($user_data['user_gendertext'] == 2) { echo $locale['uf_gendertext_male']; }
			else { echo $locale['uf_gendertext_no_data']; }
		}
		echo "</td>\n";
		echo "</tr>\n";
	}
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_gendertext";
	$db_values .= ", '".(isset($_POST['user_gendertext']) && isnum($_POST['user_gendertext']) ? $_POST['user_gendertext'] : "0")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_gendertext='".(isset($_POST['user_gendertext']) && isnum($_POST['user_gendertext']) ? $_POST['user_gendertext'] : "0")."'";
}
?>