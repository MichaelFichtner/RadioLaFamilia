<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_admin.php
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

if (!checkrights("S13") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['900'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['901'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
	}
}

if (isset($_POST['savesettings'])) {
	$error = 0;
	if(!set_mainsetting('adminmenue_icons', isnum($_POST['adminmenue_icons']) ? $_POST['adminmenue_icons'] : "0")) { $error = 1; }
	if(!set_mainsetting('adminmenue_notes', isnum($_POST['adminmenue_notes']) ? $_POST['adminmenue_notes'] : "0")) { $error = 1; }
	if(!set_mainsetting('adminmenue_userinfo', isnum($_POST['adminmenue_userinfo']) ? $_POST['adminmenue_userinfo'] : "0")) { $error = 1; }
	if(!set_mainsetting('adminmenue_nav', isset($_POST['adminmenue_nav']) ? stripinput($_POST['adminmenue_nav']) : "1")) { $error = 1; }
	if(!set_mainsetting('adminmenue_color', isset($_POST['adminmenue_color']) ? stripinput($_POST['adminmenue_color']) : "blue")) { $error = 1; }
	if(!set_mainsetting('adminmenue_version', isnum($_POST['adminmenue_version']) ? $_POST['adminmenue_version'] : "0")) { $error = 1; }
	log_admin_action("admin-4", "admin_settings_adminmenue_save");
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}


opentable($locale['adme100']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";

echo "<td width='50%' class='tbl'>".$locale['adme101']."</td>\n";
echo "<td width='50%' class='tbl'><select name='adminmenue_userinfo' class='textbox'>\n";
echo "<option value='1'".($settings['adminmenue_userinfo'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['adminmenue_userinfo'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";

echo "<td width='50%' class='tbl'>".$locale['adme102']."</td>\n";
echo "<td width='50%' class='tbl'><select name='adminmenue_nav' class='textbox'>\n";
echo "<option value='1'".($settings['adminmenue_nav'] == "1" ? " selected='selected'" : "").">".$locale['adme108']."</option>\n";
echo "<option value='sdmenue'".($settings['adminmenue_nav'] == "sdmenue" ? " selected='selected'" : "").">".$locale['adme109']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";

echo "<td width='50%' class='tbl'>".$locale['adme106']."<br /><span class='small2'>".$locale['adme107']."</span></td>\n";
echo "<td width='50%' class='tbl'><select name='adminmenue_color' class='textbox'>\n";
echo "<option value='orange'".($settings['adminmenue_color'] == "orange" ? " selected='selected'" : "").">".$locale['adme110']."</option>\n";
echo "<option value='red'".($settings['adminmenue_color'] == "red" ? " selected='selected'" : "").">".$locale['adme111']."</option>\n";
echo "<option value='blue'".($settings['adminmenue_color'] == "blue" ? " selected='selected'" : "").">".$locale['adme112']."</option>\n";
echo "<option value='baby_blue'".($settings['adminmenue_color'] == "baby_blue" ? " selected='selected'" : "").">".$locale['adme113']."</option>\n";
echo "<option value='green'".($settings['adminmenue_color'] == "green" ? " selected='selected'" : "").">".$locale['adme114']."</option>\n";
echo "<option value='pink'".($settings['adminmenue_color'] == "pink" ? " selected='selected'" : "").">".$locale['adme115']."</option>\n";
echo "<option value='black'".($settings['adminmenue_color'] == "black" ? " selected='selected'" : "").">".$locale['adme116']."</option>\n";

echo "</select></td>\n";
echo "</tr>\n<tr>\n";

echo "<td width='50%' class='tbl'>".$locale['adme103']."</td>\n";
echo "<td width='50%' class='tbl'><select name='adminmenue_version' class='textbox'>\n";
echo "<option value='1'".($settings['adminmenue_version'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['adminmenue_version'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";

echo "<td width='50%' class='tbl'>".$locale['adme104']."</td>\n";
echo "<td width='50%' class='tbl'><select name='adminmenue_icons' class='textbox'>\n";
echo "<option value='1'".($settings['adminmenue_icons'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['adminmenue_icons'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";

echo "<td width='50%' class='tbl'>".$locale['adme105']."</td>\n";
echo "<td width='50%' class='tbl'><select name='adminmenue_notes' class='textbox'>\n";
echo "<option value='1'".($settings['adminmenue_notes'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['adminmenue_notes'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";

echo "<td align='center' colspan='2' class='tbl'><br />";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once TEMPLATES."footer.php";
?>