<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_security.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| based on PHP-Fusion CMS v7.01 by Nick Jones
| http://www.php-fusion.co.uk/
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

if (!checkrights("S9") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

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
	if(!set_mainsetting('flood_interval', isnum($_POST['flood_interval']) ? $_POST['flood_interval'] : "15")) { $error = 1; }
	if(!set_mainsetting('flood_autoban', isnum($_POST['flood_autoban']) ? $_POST['flood_autoban'] : "1")) { $error = 1; }
	if(!set_mainsetting('maintenance_level', isnum($_POST['maintenance_level']) ? $_POST['maintenance_level'] : nADMIN)) { $error = 1; }
	if(!set_mainsetting('maintenance', isnum($_POST['maintenance']) ? $_POST['maintenance'] : "0")) { $error = 1; }
	if(!set_mainsetting('maintenance_message', addslash(descript($_POST['maintenance_message'])))) { $error = 1; }
	log_admin_action("admin-4", "admin_settings_security_save");
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

opentable($locale['secu100']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td class='tbl2' align='center' colspan='2'>".$locale['secu101']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['secu102']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='flood_interval' value='".$settings['flood_interval']."' maxlength='2' class='textbox' style='width:50px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['secu103']."</td>\n";
echo "<td width='50%' class='tbl'><select name='flood_autoban' class='textbox'>\n";
echo "<option value='1'".($settings['flood_autoban'] == "1" ? " selected='selected'" : "").">".$locale['secu104']."</option>\n";
echo "<option value='0'".($settings['flood_autoban'] == "0" ? " selected='selected'" : "").">".$locale['secu105']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl2' align='center' colspan='2'>".$locale['690']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['675']."</td>\n";
echo "<td width='50%' class='tbl'><select name='maintenance_level' class='textbox'>\n";
echo "<option value='".nADMIN."".($settings['maintenance_level'] == nADMIN ? " selected='selected'" : "").">".$locale['676']."</option>\n";
echo "<option value='".nSUPERADMIN."'".($settings['maintenance_level'] == nSUPERADMIN ? " selected='selected'" : "").">".$locale['677']."</option>\n";
echo "<option value='1'".($settings['maintenance_level'] == "1" ? " selected='selected'" : "").">".$locale['678']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['657']."</td>\n";
echo "<td width='50%' class='tbl'><select name='maintenance' class='textbox' style='width:50px;'>\n";
echo "<option value='1'".($settings['maintenance'] == "1" ? " selected='selected'" : "").">".$locale['502']."</option>\n";
echo "<option value='0'".($settings['maintenance'] == "0" ? " selected='selected'" : "").">".$locale['503']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['658']."</td>\n";
echo "<td width='50%' class='tbl'><textarea name='maintenance_message' cols='50' rows='5' class='textbox' style='width:200px;'>".stripslashes($settings['maintenance_message'])."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once TEMPLATES."footer.php";
?>