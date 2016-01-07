<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: welcome_panel.php
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
include LOCALE.LOCALESET."admin/settings.php";

if (!checkrights("WEL") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['error']) && (isnum($_GET['error']) OR $_GET['error'] == "pw" ) && !isset($message)) {
	if ($_GET['error'] == "0") {
		$message = $locale['900'];
	} elseif ($_GET['error'] == "1") {
		$message = $locale['901'];
	} elseif ($_GET['error'] == "pw") {
		$message = "Admin-Password incorrect";
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
	}
}

if (isset($_POST['savesettings'])) {
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		$error = 0;
		if(!set_mainsetting('siteintro', (addslashes(descript(stripslash($_POST['intro'])))))) { $error = 1; }
		if(!set_mainsetting('siteintro_collapse', isset($_POST['siteintro_collapse']) ? "1" : "0")) { $error = 1; }
		if(!set_mainsetting('siteintro_collapse_state', isset($_POST['siteintro_collapse_state']) ? "on" : "off")) { $error = 1; }
		if(!set_mainsetting('welome_panel_dis', isnum($_POST['welome_panel_dis']) ? $_POST['welome_panel_dis'] : "0")) { $error = 1; }
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		log_admin_action("admin-1", "admin_wel_panel_edited");
		redirect(FUSION_SELF.$aidlink."&error=".$error);
	} else {
		redirect(FUSION_SELF.$aidlink."&error=pw");
	}
}

opentable($locale['welc100']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='90%' class='center'>\n<tr>\n";
echo "<td valign='top' width='15%' class='tbl'>".$locale['welc101']."<br /><span class='small2'>".$locale['welc102']."<br />".$locale['welc103']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='intro' cols='80' rows='20' class='textbox'>".phpentities(stripslashes($settings['siteintro']))."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'></td><td class='tbl'>\n";
echo "<label><input type='checkbox' name='siteintro_collapse' value='yes'".($settings['siteintro_collapse'] ? " checked='checked'" : "")." /> ".$locale['welpm113']."</label><br />\n";
echo "<label><input type='checkbox' name='siteintro_collapse_state' value='yes'".($settings['siteintro_collapse_state'] == "on" ? " checked='checked'" : "")." /> ".$locale['welpm114']."</label></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl'>".$locale['welc104']."</td>\n";
echo "<td class='tbl'><select name='welome_panel_dis' class='textbox'>\n";
echo "<option value='0'".($settings['welome_panel_dis'] == "0" ? " selected='selected'" : "").">".$locale['welc105']."</option>\n";
echo "<option value='1'".($settings['welome_panel_dis'] == "1" ? " selected='selected'" : "").">".$locale['welc106']."</option>\n";
echo "<option value='2'".($settings['welome_panel_dis'] == "2" ? " selected='selected'" : "").">".$locale['welc107']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n";
if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
	echo "<tr><td class='tbl'>".$locale['853']."</td>\n";
	echo "<td class='tbl'><input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n";
	echo "</tr>\n";
}
echo "<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>";
echo "\n</table>\n</form>\n";
closetable();

require_once TEMPLATES."footer.php";
?>