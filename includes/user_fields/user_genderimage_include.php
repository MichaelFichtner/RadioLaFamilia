<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: user_genderimage_include.php
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
	echo "<td class='tbl'>".$locale['uf_genderimage_name']."</td>\n";
	echo "<td class='tbl'>";
	echo "<input type='radio' name='user_genderimage' value='0' ".(isset($user_data['user_genderimage']) && $user_data['user_genderimage'] == 0 ? "checked='checked'" : "")." /> ".$locale['uf_genderimage_no_data'];
	echo " <input type='radio' name='user_genderimage' value='1' ".(isset($user_data['user_genderimage']) && $user_data['user_genderimage'] == 1 ? "checked='checked'" : "")." /> ".$locale['uf_genderimage_female'];
	echo " <input type='radio' name='user_genderimage' value='2' ".(isset($user_data['user_genderimage']) && $user_data['user_genderimage'] == 2 ? "checked='checked'" : "")." /> ".$locale['uf_genderimage_male'];
	echo "</td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	if ($user_data['user_genderimage']) {
		echo "<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_genderimage_name']."</td>\n";
		echo "<td align='right' class='tbl1'>";
		if (isset($user_data['user_genderimage'])) {
			if ($user_data['user_genderimage'] == 1) { echo "<img src='".IMAGES."gender/female.png' alt='".$locale['uf_genderimage_female']."' />"; }
			elseif ($user_data['user_genderimage'] == 2) { echo "<img src='".IMAGES."gender/male.png' alt='".$locale['uf_genderimage_male']."' />"; }
			else { echo $locale['no_data']; }
		}
		echo "</td>\n";
		echo "</tr>\n";
	}
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_genderimage";
	$db_values .= ", '".(isset($_POST['user_genderimage']) && isnum($_POST['user_genderimage']) ? $_POST['user_genderimage'] : "0")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_genderimage='".(isset($_POST['user_genderimage']) && isnum($_POST['user_genderimage']) ? $_POST['user_genderimage'] : "0")."'";
}
?>