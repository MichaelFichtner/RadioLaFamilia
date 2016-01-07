<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_language.php
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

if (!checkrights("LAN") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

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
	
	$localeset = stripinput($_POST['localeset']);
	$locale_content = stripinput($_POST['locale_content']);
	
	$locale_content = explode(",", $locale_content);
	$language_files = makefilelist(LOCALE, ".|..", true, "folders");
	$new_locale_content = '';
	for ($i = 0; $i < count($locale_content); $i++) {
		$value = trim($locale_content[$i]);
		if($value != '' && in_array($value, $language_files)) {
		$new_locale_content .= $value.",";
		}
	}
	if(substr($new_locale_content, -1) == ",") $new_locale_content = substr($new_locale_content, 0, -1);
	
	if(!set_mainsetting('locale', $localeset)) { $error = 1; }
	if(!set_mainsetting('locale_multi', isnum($_POST['multilanguage']) ? $_POST['multilanguage'] : "0")) { $error = 1; }
	if(!set_mainsetting('locale_multi_forum', isnum($_POST['multilanguage_forum']) ? $_POST['multilanguage_forum'] : "0")) { $error = 1; }
	if(!set_mainsetting('locale_multi_shout', isnum($_POST['multilanguage_shout']) ? $_POST['multilanguage_shout'] : "0")) { $error = 1; }
	if(!set_mainsetting('locale_content', $new_locale_content)) { $error = 1; }
	log_admin_action("admin-4", "admin_settings_language_save");
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

$settings2 = array();
$result = dbquery("SELECT settings_name, settings_value FROM ".DB_SETTINGS);
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}
$locale_files = makefilelist(LOCALE, ".|..", true, "folders");

opentable($locale['lang100']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['lang101']."</td>\n";
echo "<td width='50%' class='tbl'><select name='localeset' class='textbox'>\n";
echo makefileopts($locale_files, $settings2['locale'])."\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['lang102']."</td>\n";
echo "<td width='50%' class='tbl'><select name='multilanguage' class='textbox'>\n";
echo "<option value='1'".($settings2['locale_multi'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['locale_multi'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['lang107']."</td>\n";
echo "<td width='50%' class='tbl'><select name='multilanguage_forum' class='textbox'>\n";
echo "<option value='1'".($settings2['locale_multi_forum'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['locale_multi_forum'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['lang108']."</td>\n";
echo "<td width='50%' class='tbl'><select name='multilanguage_shout' class='textbox'>\n";
echo "<option value='1'".($settings2['locale_multi_shout'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['locale_multi_shout'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['lang103']."<br /><span class='small2'>".$locale['lang104']."<br />".$locale['lang105']."<br />".$locale['lang106']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='locale_content' cols='50' rows='6' class='textbox' style='width:230px;'>".$settings2['locale_content']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once TEMPLATES."footer.php";
?>