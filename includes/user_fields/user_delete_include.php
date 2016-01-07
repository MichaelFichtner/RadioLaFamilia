<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: user_delete_include.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Author: Keddy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if ($profile_method == "input" && (!isset($register) || $register == false)) {
	
	echo "<tr>\n";
	echo "<td class='tbl'>".$locale['uf_delete_user']."\n</td>\n";
	echo "<td class='tbl'><input type='checkbox' name='delete_user' id='delete_user' value='1' /></label></td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	//Nothing here
} elseif ($profile_method == "validate_insert") {
	//Nothing here
} elseif ($profile_method == "validate_update") {
		$db_values .= ", user_status='".(isset($_POST['delete_user']) ? "5" : "0")."'";
}
?>