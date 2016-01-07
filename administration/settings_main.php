<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_main.php
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

if (!checkrights("S1") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

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
	$sitefooter = descript(stripslash($_POST['footer']));
	
	if(!set_mainsetting('sitename', stripinput($_POST['sitename']))) { $error = 1; }
	if(!set_mainsetting('sitetitle', stripinput($_POST['sitetitle']))) { $error = 1; }
	if(!set_mainsetting('siteurl', stripinput($_POST['siteurl']).(strrchr($_POST['siteurl'],"/") != "/" ? "/" : ""))) { $error = 1; }
	if(!set_mainsetting('sitebanner', stripinput($_POST['sitebanner']))) { $error = 1; }
	if(!set_mainsetting('siteemail', stripinput($_POST['siteemail']))) { $error = 1; }
	if(!set_mainsetting('siteusername', stripinput($_POST['username']))) { $error = 1; }
	if(!set_mainsetting('description', stripinput($_POST['description']))) { $error = 1; }
	if(!set_mainsetting('keywords', stripinput($_POST['keywords']))) { $error = 1; }
	if(!set_mainsetting('footer', (addslashes($sitefooter)))) { $error = 1; }
	if(!set_mainsetting('opening_page', stripinput($_POST['opening_page']))) { $error = 1; }
	if(!set_mainsetting('theme', stripinput($_POST['theme']))) { $error = 1; }
	if(!set_mainsetting('default_search', stripinput($_POST['default_search']))) { $error = 1; }
	if(!set_mainsetting('exclude_left', stripinput($_POST['exclude_left']))) { $error = 1; }
	if(!set_mainsetting('exclude_upper', stripinput($_POST['exclude_upper']))) { $error = 1; }
	if(!set_mainsetting('exclude_lower', stripinput($_POST['exclude_lower']))) { $error = 1; }
	if(!set_mainsetting('exclude_right', stripinput($_POST['exclude_right']))) { $error = 1; }
	log_admin_action("admin-4", "admin_settings_main_save");
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

$settings2 = array();
$result = dbquery("SELECT settings_name, settings_value FROM ".DB_SETTINGS);
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}
$theme_files = makefilelist(THEMES, ".|..|templates", true, "folders");

ob_start();
opentable($locale['400']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['402']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='sitename' value='".$settings2['sitename']."' maxlength='255' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['402a']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='sitetitle' value='".$settings2['sitetitle']."' maxlength='255' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['403']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='siteurl' value='".$settings2['siteurl']."' maxlength='255' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['404']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='sitebanner' value='".$settings2['sitebanner']."' maxlength='255' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['405']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='siteemail' value='".$settings2['siteemail']."' maxlength='128' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['406']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='username' value='".$settings2['siteusername']."' maxlength='32' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['409']."</td>\n";
echo "<td width='50%' class='tbl'><textarea name='description' cols='50' rows='6' class='textbox' style='width:230px;'>".$settings2['description']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['410']."<br /><span class='small2'>".$locale['411']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='keywords' cols='50' rows='6' class='textbox' style='width:230px;'>".$settings2['keywords']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['412']."</td>\n";
echo "<td width='50%' class='tbl'><textarea name='footer' cols='50' rows='6' class='textbox' style='width:230px;'>".phpentities(stripslashes($settings2['footer']))."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' class='tbl'>".$locale['413']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='opening_page' value='".$settings2['opening_page']."' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['418'];
if ($userdata['user_theme'] == "Default") {
  if ($settings2['theme'] != str_replace(THEMES, "", substr(THEME, 0, strlen(THEME)-1))) {
  	echo "<div class='admin-message'>".$locale['global_302']."</div>\n";
  }
}
echo "</td>\n";
echo "<td width='50%' class='tbl'><select name='theme' class='textbox'>\n";
echo makefileopts($theme_files, $settings2['theme'])."\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['419']."</td>\n";
echo "<td width='50%' class='tbl'><select name='default_search' class='textbox'>\n[DEFAULT_SEARCH]</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['420']."<br /><span class='small2'>".$locale['424']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='exclude_left' cols='50' rows='5' class='textbox' style='width:230px;'>".$settings2['exclude_left']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['421']."<br /><span class='small2'>".$locale['424']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='exclude_upper' cols='50' rows='5' class='textbox' style='width:230px;'>".$settings2['exclude_upper']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['422']."<br /><span class='small2'>".$locale['424']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='exclude_lower' cols='50' rows='5' class='textbox' style='width:230px;'>".$settings2['exclude_lower']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['423']."<br /><span class='small2'>".$locale['424']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='exclude_right' cols='50' rows='5' class='textbox' style='width:230px;'>".$settings2['exclude_right']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

$cache = ob_get_contents();

unset($locale);
$default_search = "";
$dh = opendir(LOCALE.LOCALESET."search");
while (false !== ($entry = readdir($dh))) {
	if (substr($entry, 0, 1) != "." && $entry != "index.php") {
		echo "ENTRY: $entry<br />";
		include LOCALE.LOCALESET."search/".$entry;
		foreach ($locale as $key => $value) {
			if (preg_match("/400/", $key)) {
				$entry = str_replace(".php", "", $entry);
				$default_search .= "<option value='".$entry."'".($settings2['default_search'] == $entry ? " selected='selected'" : "").">".$value."</option>\n";
			}
		}
		unset($locale);
	}
}
closedir($dh); unset($locale);
include LOCALE.LOCALESET."search.php";
$default_search .= "<option value='all'".($settings2['default_search'] == 'all' ? " selected='selected'" : "").">".$locale['407']."</option>\n";
$cache = str_replace("[DEFAULT_SEARCH]", $default_search, $cache);
ob_end_clean();
echo $cache;

@require LOCALE.LOCALESET."global.php";

require_once TEMPLATES."footer.php";
?>