<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: administration/contact_page.php
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
require_once "../maincore.php";
require_once TEMPLATES."admin_header.php";
include LOCALE.LOCALESET."admin/contact_page.php";

if (!checkrights("WEL") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['error']) && (isnum($_GET['error']) || $_GET['error'] == "pw" ) && !isset($message)) {
	if ($_GET['error'] == "0") {
		$message = $locale['con900'];
	} elseif ($_GET['error'] == "1") {
		$message = $locale['con901'];
	} elseif ($_GET['error'] == "pw") {
		$message = $locale['con903'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
	}
}

if (isset($_POST['savesettings'])) {
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		$error = 0;
		if(!set_mainsetting('contact_site', (addslashes(descript(stripslash($_POST['contact_site'])))))) { $error = 1; }
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		log_admin_action("admin-1", "admin_contact_page");
		redirect(FUSION_SELF.$aidlink."&error=".$error);
	} else {
		redirect(FUSION_SELF.$aidlink."&error=pw");
	}
}


opentable($locale['con100']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='90%' class='center'>\n<tr>\n";
echo "<td valign='top' width='15%' class='tbl'>".$locale['con101']."<br /><span class='small2'>".$locale['con102']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='contact_site' cols='80' rows='20' class='textbox'>".phpentities(stripslashes($settings['contact_site']))."</textarea></td>\n";
echo "</tr>\n";
if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
	echo "<tr><td class='tbl'>".$locale['con104']."</td>\n";
	echo "<td class='tbl'><input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />";
echo "<input type='submit' name='savesettings' value='".$locale['con103']."' class='button' /></td>\n";
echo "</tr>";
echo "\n</table>\n</form>\n";
closetable();

require_once TEMPLATES."footer.php";
?>