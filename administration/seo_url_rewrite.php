<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: seo_url_rewrite.php
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
include LOCALE.LOCALESET."admin/seo_url_rewrite.php";

if (!checkrights("SEO") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['seo900'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['seo901'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
	}
}

if (isset($_POST['savesettings'])) {
	$error = 0;
	if(!set_mainsetting('seo_url_rewrite', stripinput($_POST['seo_url_rewrite']))) { $error = 1; }
	log_admin_action("admin-3", "admin_seo_settings_saved");
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

$settings2 = array();
$result = dbquery("SELECT settings_name, settings_value FROM ".DB_SETTINGS);
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}

opentable($locale['seo200']);

if(MOD_REWRITE_ABLE) {
echo "<div class='tbl2'>".$locale['seo201']."<br />
".$locale['seo202']."<br />
".$locale['seo203']."<br />
".$locale['seo204']."</div><br /><br />";
} elseif(!file_exists(BASEDIR.".htaccess")) {
echo "<div class='tbl2'>".$locale['seo201']."<br />
".$locale['seo205']."<br />
".$locale['seo206']."</div><br /><br />";
} else {
echo "<div class='tbl2'>".$locale['seo201']."<br />
".$locale['seo207']."<br />
".$locale['seo208']."</div><br /><br />";
}

echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['seo210']."</td>\n";
echo "<td width='50%' class='tbl'><select name='seo_url_rewrite' class='textbox'".(MOD_REWRITE_ABLE ? "" : " disabled='disabled'").">\n";
echo "<option value='1'".($settings2['seo_url_rewrite'] == "1" ? " selected='selected'" : "").">".$locale['seo211']."</option>\n";
echo "<option value='0'".($settings2['seo_url_rewrite'] == "0" ? " selected='selected'" : "").">".$locale['seo212']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />";
if(MOD_REWRITE_ABLE) {
echo "<input type='submit' name='savesettings' value='".$locale['seo250']."' class='button' />";
} else {
echo "&nbsp;";
}
echo "</td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once TEMPLATES."footer.php";
?>