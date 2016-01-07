<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_users.php
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

	if ($_POST['enable_deactivation'] == '0') {
		$result = dbquery("UPDATE ".DB_USERS." SET user_status='0' WHERE user_status='5'");
		if (!$result) { $error = 1; }
	}
	
	if(!set_mainsetting('enable_deactivation', isnum($_POST['enable_deactivation']) ? $_POST['enable_deactivation'] : "0")) { $error = 1; }
	if(!set_mainsetting('deactivation_period', isnum($_POST['deactivation_period']) ? $_POST['deactivation_period'] : "365")) { $error = 1; }
	if(!set_mainsetting('deactivation_response', isnum($_POST['deactivation_response']) ? $_POST['deactivation_response'] : "14")) { $error = 1; }
	if(!set_mainsetting('deactivation_action', isnum($_POST['deactivation_action']) ? $_POST['deactivation_action'] : "0")) { $error = 1; }
	if(!set_mainsetting('allowtochange_username', isnum($_POST['allowtochange_username']) ? $_POST['allowtochange_username'] : "0")) { $error = 1; }
	if(!set_mainsetting('hide_userprofiles', isnum($_POST['hide_userprofiles']) ? $_POST['hide_userprofiles'] : "0")) { $error = 1; }
	if(!set_mainsetting('hide_groupprofiles', isnum($_POST['hide_groupprofiles']) ? $_POST['hide_groupprofiles'] : "0")) { $error = 1; }
	if(!set_mainsetting('avatar_filesize', isnum($_POST['avatar_filesize']) ? $_POST['avatar_filesize'] : "15000")) { $error = 1; }
	if(!set_mainsetting('extern_avatar_upload', isnum($_POST['extern_avatar_upload']) ? $_POST['extern_avatar_upload'] : "0")) { $error = 1; }
	if(!set_mainsetting('avatar_width', isnum($_POST['avatar_width']) ? $_POST['avatar_width'] : "100")) { $error = 1; }
	if(!set_mainsetting('avatar_height', isnum($_POST['avatar_height']) ? $_POST['avatar_height'] : "100")) { $error = 1; }
	if(!set_mainsetting('avatar_ratio', isnum($_POST['avatar_ratio']) ? $_POST['avatar_ratio'] : "0")) { $error = 1; }
	if(!set_mainsetting('userthemes', isnum($_POST['userthemes']) ? $_POST['userthemes'] : "0")) { $error = 1; }
	log_admin_action("admin-4", "admin_settings_users_save");
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

opentable($locale['1000']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td class='tbl2' align='center' colspan='2'>".$locale['1015']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='70%' class='tbl'>".$locale['1002']."</td>\n";
echo "<td class='tbl' width='30%'><select name='enable_deactivation' class='textbox'>\n";
echo "<option value='0'".($settings['enable_deactivation'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "<option value='1'".($settings['enable_deactivation'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='70%' class='tbl'>".$locale['1003']."<br /><span class='small2'>".$locale['1004']."</span></td>\n";
echo "<td width='30%' class='tbl'><input type='text' name='deactivation_period' value='".$settings['deactivation_period']."' maxlength='3' class='textbox' style='width:30px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='70%' class='tbl'>".$locale['1005']."<br /><span class='small2'>".$locale['1006']."</span></td>\n";
echo "<td width='30%' class='tbl'><input type='text' name='deactivation_response' value='".$settings['deactivation_response']."' maxlength='3' class='textbox' style='width:30px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='70%' class='tbl'>".$locale['1011']."</td>\n";
echo "<td class='tbl' width='30%'><select name='deactivation_action' class='textbox'>\n";
echo "<option value='0'".($settings['deactivation_action'] == "0" ? " selected='selected'" : "").">".$locale['1012']."</option>\n";
echo "<option value='1'".($settings['deactivation_action'] == "1" ? " selected='selected'" : "").">".$locale['1013']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl2' align='center' colspan='2'>".$locale['1007']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1016']."</td>\n";
echo "<td width='50%' class='tbl'><select name='allowtochange_username' class='textbox'>\n";
echo "<option value='1'".($settings['allowtochange_username'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['allowtochange_username'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1017']."</td>\n";
echo "<td width='50%' class='tbl'><select name='hide_userprofiles' class='textbox'>\n";
echo "<option value='1'".($settings['hide_userprofiles'] == 1 ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['hide_userprofiles'] == 0 ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1018']."</td>\n";
echo "<td width='50%' class='tbl'><select name='hide_groupprofiles' class='textbox'>\n";
echo "<option value='1'".($settings['hide_groupprofiles'] == 1 ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['hide_groupprofiles'] == 0 ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1008']."<br /><span class='small2'>(".$locale['1009'].")</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='avatar_width' value='".$settings['avatar_width']."' maxlength='3' class='textbox' style='width:40px;' /> x\n";
echo "<input type='text' name='avatar_height' value='".$settings['avatar_height']."' maxlength='3' class='textbox' style='width:40px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1010']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='avatar_filesize' value='".$settings['avatar_filesize']."' maxlength='10' class='textbox' style='width:100px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1019']."</td>\n";
echo "<td width='50%' class='tbl'><select name='extern_avatar_upload' class='textbox'>\n";
echo "<option value='1'".($settings['extern_avatar_upload'] == 1 ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['extern_avatar_upload'] == 0 ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1001']."</td>\n";
echo "<td width='50%' class='tbl'><select name='avatar_ratio' class='textbox'>\n";
echo "<option value='0'".($settings['avatar_ratio'] == 0 ? " selected='selected'" : "").">".$locale['955']."</option>\n";
echo "<option value='1'".($settings['avatar_ratio'] == 1 ? " selected='selected'" : "").">".$locale['956']."</option>\n";
echo "</select>\n</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['1020']."?</td>\n";
echo "<td width='50%' class='tbl'><select name='userthemes' class='textbox'>\n";
echo "<option value='1'".($settings['userthemes'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['userthemes'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once TEMPLATES."footer.php";
?>