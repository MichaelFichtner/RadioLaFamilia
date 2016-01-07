<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_ipp.php
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

if (!checkrights("S10") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

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
	if (isnum($_POST['newsperpage'])) {
		if ($_POST['newsperpage'] % 2 == 0) {
			$_POST['newsperpage']++;
		}
	} else {
		$_POST['newsperpage'] = 11;
	}
	if(!set_mainsetting('newsperpage', isnum($_POST['newsperpage']) ? $_POST['newsperpage'] : "11")) { $error = 1; }
	if(!set_mainsetting('articles_per_page', isnum($_POST['articles_per_page']) ? $_POST['articles_per_page'] : "15")) { $error = 1; }
	if(!set_mainsetting('downloads_per_page', isnum($_POST['downloads_per_page']) ? $_POST['downloads_per_page'] : "15")) { $error = 1; }
	if(!set_mainsetting('links_per_page', isnum($_POST['links_per_page']) ? $_POST['links_per_page'] : "15")) { $error = 1; }
	if(!set_mainsetting('posts_per_page', isnum($_POST['posts_per_page']) ? $_POST['posts_per_page'] : "20")) { $error = 1; }
	if(!set_mainsetting('threads_per_page', isnum($_POST['threads_per_page']) ? $_POST['threads_per_page'] : "20")) { $error = 1; }
	if(!set_mainsetting('numofshouts', isnum($_POST['numofshouts']) ? $_POST['numofshouts'] : "10")) { $error = 1; }
	if(!set_mainsetting('last_seen_users_show', isnum($_POST['last_seen_users_show']) ? $_POST['last_seen_users_show'] : "10")) { $error = 1; }
	if(!set_mainsetting('last_seen_users_show_more', isnum($_POST['last_seen_users_show_more']) ? $_POST['last_seen_users_show_more'] : "10")) { $error = 1;}
	log_admin_action("admin-4", "admin_settings_ipp_save");
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

$settings2 = array();
$result = dbquery("SELECT settings_name, settings_value FROM ".DB_SETTINGS);
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}

opentable($locale['400']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['669'].":</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='newsperpage' value='".$settings2['newsperpage']."' maxlength='2' class='textbox' style='width:50px;' /> (".$locale['670'].")</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['910'].":</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='articles_per_page' value='".$settings2['articles_per_page']."' maxlength='2' class='textbox' style='width:50px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['911'].":</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='downloads_per_page' value='".$settings2['downloads_per_page']."' maxlength='2' class='textbox' style='width:50px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['912'].":</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='links_per_page' value='".$settings2['links_per_page']."' maxlength='2' class='textbox' style='width:50px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['913'].":</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='posts_per_page' value='".$settings2['posts_per_page']."' maxlength='2' class='textbox' style='width:50px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['914'].":</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='threads_per_page' value='".$settings2['threads_per_page']."' maxlength='2' class='textbox' style='width:50px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['656']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='numofshouts' value='".$settings2['numofshouts']."' maxlength='2' class='textbox' style='width:50px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['915']."<br /><span class='small'>".$locale['916']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='last_seen_users_show' value='".$settings2['last_seen_users_show']."' maxlength='2' class='textbox' style='width:50px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['917']."<br /><span class='small'>".$locale['918']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='last_seen_users_show_more' value='".$settings2['last_seen_users_show_more']."' maxlength='2' class='textbox' style='width:50px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' />\n</td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once TEMPLATES."footer.php";
?>