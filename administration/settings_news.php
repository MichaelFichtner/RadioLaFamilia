<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_news.php
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

if (!checkRights("S8") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

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
	if(!set_mainsetting('news_style', isnum($_POST['news_style']) ? $_POST['news_style'] : "0")) { $error = 1; }
	if(!set_mainsetting('news_image_link', isnum($_POST['news_image_link']) ? $_POST['news_image_link'] : "0")) { $error = 1; }
	if(!set_mainsetting('news_image_frontpage', isnum($_POST['news_image_frontpage']) ? $_POST['news_image_frontpage'] : "0")) { $error = 1; }
	if(!set_mainsetting('news_image_readmore', isnum($_POST['news_image_readmore']) ? $_POST['news_image_readmore'] : "0")) { $error = 1; }
	if(!set_mainsetting('news_thumb_ratio', isnum($_POST['news_thumb_ratio']) ? $_POST['news_thumb_ratio'] : "0")) { $error = 1; }
	if(!set_mainsetting('news_thumb_w', isnum($_POST['news_thumb_w']) ? $_POST['news_thumb_w'] : "100")) { $error = 1; }
	if(!set_mainsetting('news_thumb_h', isnum($_POST['news_thumb_h']) ? $_POST['news_thumb_h'] : "100")) { $error = 1; }
	if(!set_mainsetting('news_photo_w', isnum($_POST['news_photo_w']) ? $_POST['news_photo_w'] : "400")) { $error = 1; }
	if(!set_mainsetting('news_photo_h', isnum($_POST['news_photo_h']) ? $_POST['news_photo_h'] : "300")) { $error = 1; }
	if(!set_mainsetting('news_photo_max_w', isnum($_POST['news_photo_max_w']) ? $_POST['news_photo_max_w'] : "1800")) { $error = 1; }
	if(!set_mainsetting('news_photo_max_h', isnum($_POST['news_photo_max_h']) ? $_POST['news_photo_max_h'] : "1600")) { $error = 1; }
	if(!set_mainsetting('news_photo_max_b', isnum($_POST['news_photo_max_b']) ? $_POST['news_photo_max_b'] : "150000")) { $error = 1; }
	log_admin_action("admin-4", "admin_settings_news_save");
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
echo "<td width='50%' class='tbl'>".$locale['414']."</td>\n";
echo "<td width='50%' class='tbl'><select name='news_style' class='textbox'>\n";
echo "<option value='0'".($settings2['news_style'] == 0 ? " selected='selected'" : "").">".$locale['415']."</option>\n";
echo "<option value='1'".($settings2['news_style'] == 1 ? " selected='selected'" : "").">".$locale['416']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['951']."</td>\n";
echo "<td width='50%' class='tbl'><select name='news_image_link' class='textbox'>\n";
echo "<option value='0'".($settings2['news_image_link'] == 0 ? " selected='selected'" : "").">".$locale['952']."</option>\n";
echo "<option value='1'".($settings2['news_image_link'] == 1 ? " selected='selected'" : "").">".$locale['953']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['957']."</td>\n";
echo "<td width='50%' class='tbl'><select name='news_image_frontpage' class='textbox'>\n";
echo "<option value='0'".($settings2['news_image_frontpage'] == 0 ? " selected='selected'" : "").">".$locale['959']."</option>\n";
echo "<option value='1'".($settings2['news_image_frontpage'] == 1 ? " selected='selected'" : "").">".$locale['960']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['958']."</td>\n";
echo "<td width='50%' class='tbl'><select name='news_image_readmore' class='textbox'>\n";
echo "<option value='0'".($settings2['news_image_readmore'] == 0 ? " selected='selected'" : "").">".$locale['959']."</option>\n";
echo "<option value='1'".($settings2['news_image_readmore'] == 1 ? " selected='selected'" : "").">".$locale['960']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl2' align='center' colspan='2'>".$locale['950']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['954']."</td>\n";
echo "<td width='50%' class='tbl'><select name='news_thumb_ratio' class='textbox'>\n";
echo "<option value='0'".($settings2['news_thumb_ratio'] == 0 ? " selected='selected'" : "").">".$locale['955']."</option>\n";
echo "<option value='1'".($settings2['news_thumb_ratio'] == 1 ? " selected='selected'" : "").">".$locale['956']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['601']."<br /><span class='small2'>".$locale['604']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='news_thumb_w' value='".$settings2['news_thumb_w']."' maxlength='3' class='textbox' style='width:40px;' /> x\n";
echo "<input type='text' name='news_thumb_h' value='".$settings2['news_thumb_h']."' maxlength='3' class='textbox' style='width:40px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['602']."<br /><span class='small2'>".$locale['604']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='news_photo_w' value='".$settings2['news_photo_w']."' maxlength='3' class='textbox' style='width:40px;' /> x\n";
echo "<input type='text' name='news_photo_h' value='".$settings2['news_photo_h']."' maxlength='3' class='textbox' style='width:40px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['603']."<br /><span class='small2'>".$locale['604']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='news_photo_max_w' value='".$settings2['news_photo_max_w']."' maxlength='4' class='textbox' style='width:40px;' /> x\n";
echo "<input type='text' name='news_photo_max_h' value='".$settings2['news_photo_max_h']."' maxlength='4' class='textbox' style='width:40px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['605']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='news_photo_max_b' value='".$settings2['news_photo_max_b']."' maxlength='10' class='textbox' style='width:100px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' />\n</td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once TEMPLATES."footer.php";
?>