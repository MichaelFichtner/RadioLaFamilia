<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: user_trank_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Author: Fangree Productions, Fangree_Craig
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }


if ($profile_method == "input") {
if (iADMIN && checkrights("C")){
	echo "<tr>\n";
	echo "<td class='tbl' valign='top'>".$locale['uf_001']." </td>\n";
	echo "<td class='tbl'><input type='text' name='user_trank' value='".(isset($user_data['user_trank']) ? $user_data['user_trank'] : "")."' maxlength='75' class='textbox' style='width:295px;' /></td>\n";
	echo "</tr>\n";
   } else {
       echo "";
   }
} elseif ($profile_method == "display") {
	if ($user_data['user_trank']) {
			echo "<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_001']." </td>\n";
		echo "<td align='right' class='tbl1'>";
		echo "".$user_data['user_trank']."\n";	
		echo "</td>\n</tr>\n";
	}
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_trank";
	$db_values .= ", '".(isset($_POST['user_trank']) ? stripinput(trim($_POST['user_trank'])) : "")."'";

} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_trank='".(isset($_POST['user_trank']) ? stripinput(trim($_POST['user_trank'])) : "")."'";}
?>