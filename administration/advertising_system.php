<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: advertising_system.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter, Wooya
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";
require_once TEMPLATES."admin_header.php";

if (!checkrights("ADS") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (file_exists(LOCALE.LOCALESET."admin/ads_system.php")) {
	include LOCALE.LOCALESET."admin/ads_system.php";
} else {
	include LOCALE."English/admin/ads_system.php";
}

if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 1; }

if (isset($_GET['error'])) {
	if ($_GET['error'] == 0) {
		$message = $locale['ads_saved'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['ads_not_saved'];
	} elseif ($_GET['error'] == 2) {
		$message = $locale['global_182'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n";
	}
}

// Navigation
$navigation = "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
$navigation .= "<td width='33%' align='center' class='".($_GET['page'] == 1 ? "tbl2" : "tbl1")."'>".($_GET['page'] == 1 ? "<strong>" : "");
$navigation .= "<a href='".FUSION_SELF.$aidlink."&amp;page=1'>".$locale['ads_page1']."</a>".($_GET['page']== 1 ? "</strong>" : "")."</td>\n";
$navigation .= "<td width='33%' align='center' class='".($_GET['page'] == 2 ? "tbl2" : "tbl1")."'>".($_GET['page'] == 2 ? "<strong>" : "");
$navigation .= "<a href='".FUSION_SELF.$aidlink."&amp;page=2'>".$locale['ads_page2']."</a>".($_GET['page'] == 2 ? "</strong>" : "")."</td>\n";
$navigation .= "</tr>\n</table>\n";
$navigation .= "<div style='margin:5px'></div>\n";

if ($_GET['page'] == 1) {

if (isset($_POST['ads_save'])) {
	$error = 0;
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		if(!set_mainsetting('ads_vf_show', isnum($_POST['ads_vf_show']) ? $_POST['ads_vf_show'] : 0)) { $error = 1; }
		if(!set_mainsetting('ads_vf_code', (addslashes(stripslash($_POST['ads_vf_code']))))) { $error = 1; }
		if(!set_mainsetting('ads_vf_name', isset($_POST['ads_vf_name']) ? stripinput($_POST['ads_vf_name']) : "")) { $error = 1; }
		if(!set_mainsetting('ads_vf_display', isnum($_POST['ads_vf_display']) ? $_POST['ads_vf_display'] : 0)) { $error = 1; }
		log_admin_action("admin-3", "admin_adssystem_vf_save");
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		redirect(FUSION_SELF.$aidlink."&amp;error=".$error);
	} else {
		redirect(FUSION_SELF.$aidlink."&amp;error=2");
	}
}

opentable($locale['ads_title']);
echo $navigation;
echo "<form name='ads_form' method='post' action='".FUSION_SELF.$aidlink."&amp;page=1'>\n";
echo "<table cellpadding='0' cellspacing='0' width='450' align='center'>\n";
echo "<tr>\n";
echo "<td class='tbl'>".$locale['ads_name']."</td>";
echo "<td class='tbl'><input type='text' class='textbox' name='ads_vf_name' value='".$settings['ads_vf_name']."' style='width:500px' /></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td valign='top' class='tbl'>".$locale['ads_code']."<br /><span class='small2'><em>".$locale['ads_code_info']."</em></span></td>";
echo "<td class='tbl'><textarea rows='20' class='textbox' name='ads_vf_code' style='width:500px'>".phpentities(stripslashes($settings['ads_vf_code']))."</textarea></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class='tbl'>".$locale['ads_display']."</td>";
echo "<td class='tbl'>";
echo "<select class='textbox' name='ads_vf_display'>\n";
echo "<option value='0'".($settings['ads_vf_display']==0 ? " selected='selected'" : "").">".$locale['ads_random']."</option>\n";
echo "<option value='1'".($settings['ads_vf_display']==1 ? " selected='selected'" : "").">".$locale['ads_before']."</option>\n";
for ($i=2;$i<22;$i++) {
	echo "<option value='".$i."'".($settings['ads_vf_display']==$i ? " selected='selected'" : "").">".sprintf($locale['ads_after'], ($i-1))."</option>\n";
}
echo "</select>\n";
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td class='tbl'>".$locale['ads_show']."</td>";
echo "<td class='tbl'><input type='checkbox' name='ads_vf_show' value='1' ".($settings['ads_vf_show'] ? "checked='checked' " : "")."/></td>";
echo "</tr><tr>\n";
if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		echo "<td class='tbl'>Admin Password:</td>\n";
		echo "<td class='tbl'><input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n";
		echo "</tr>\n<tr>\n";
	}
echo "<td colspan='2' align='center' class='tbl'><input type='submit' class='button' name='ads_save' value='".$locale['ads_save']."' /></td></tr>\n";
echo "</table>\n";
echo "</form>\n";
closetable();

} elseif ($_GET['page'] == 2) {

if (isset($_POST['ads_save'])) {
	$error = 0;
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		if(!set_mainsetting('ads_in_name', stripinput($_POST['ads_in_name']))) { $error = 1; }
		if(!set_mainsetting('ads_in_show', isnum($_POST['ads_in_show']) ? $_POST['ads_in_show'] : "0")) { $error = 1; }
		if(!set_mainsetting('ads_in_code', (addslashes(stripslash($_POST['ads_in_code']))))) { $error = 1; }
		log_admin_action("admin-3", "admin_adssystem_in_save");
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		redirect(FUSION_SELF.$aidlink."&amp;page=2&amp;error=".$error);
	} else {
		redirect(FUSION_SELF.$aidlink."&amp;page=2&amp;error=2");
	}
}


opentable($locale['ads_title']);
echo $navigation;
echo "<form name='ads_form' method='post' action='".FUSION_SELF.$aidlink."&amp;page=2'>\n";
echo "<table cellpadding='0' cellspacing='0' width='450' align='center'>\n";
echo "<tr>\n";
echo "<td class='tbl'>".$locale['ads_name']."</td>";
echo "<td class='tbl'><input type='text' class='textbox' name='ads_in_name' value='".$settings['ads_in_name']."' style='width:500px' /></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td valign='top' class='tbl'>".$locale['ads_code']."<br /><span class='small2'><em>".$locale['ads_code_info']."</em></span></td>";
echo "<td class='tbl'><textarea rows='20' class='textbox' name='ads_in_code' style='width:500px'>".phpentities(stripslashes($settings['ads_in_code']))."</textarea></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<tr>\n";
echo "<td class='tbl'>".$locale['ads_show']."</td>";
echo "<td class='tbl'><input type='checkbox' name='ads_in_show' value='1' ".($settings['ads_in_show'] ? "checked='checked' " : "")."/></td>";
echo "</tr><tr>\n";
if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		echo "<td class='tbl'>Admin Password:</td>\n";
		echo "<td class='tbl'><input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n";
		echo "</tr>\n<tr>\n";
	}
echo "<td colspan='2' align='center' class='tbl'><input type='submit' class='button' name='ads_save' value='".$locale['ads_save']."' /></td></tr>\n";
echo "</table>\n";
echo "</form>\n";
closetable();

}

require_once TEMPLATES."footer.php";
?>