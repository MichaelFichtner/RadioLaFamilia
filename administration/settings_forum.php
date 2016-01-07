<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_forum.php
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

if (!checkrights("S3") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 1; }

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['900'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['901'];
	} elseif ($_GET['error'] == 2) {
		$message = $locale['global_182'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n";
	}
}

// Navigation
$navigation = "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
$navigation .= "<td width='33%' align='center' class='".($_GET['page']==1?"tbl2":"tbl1")."'>".($_GET['page']==1?"<strong>":"")."<a href='".FUSION_SELF.$aidlink."&amp;page=1'>".$locale['540']."</a>".($_GET['page']==1?"</strong>":"")."</td>\n";
$navigation .= "<td width='33%' align='center' class='".($_GET['page']==2?"tbl2":"tbl1")."'>".($_GET['page']==2?"<strong>":"")."<a href='".FUSION_SELF.$aidlink."&amp;page=2'>".$locale['541']."</a>".($_GET['page']==2?"</strong>":"")."</td>\n";
$navigation .= "<td width='33%' align='center' class='".($_GET['page']==3?"tbl2":"tbl1")."'>".($_GET['page']==3?"<strong>":"")."<a href='".FUSION_SELF.$aidlink."&amp;page=3'>".$locale['542']."</a>".($_GET['page']==3?"</strong>":"")."</td>\n";
$navigation .= "</tr>\n</table>\n";
$navigation .= "<div style='margin:5px'></div>\n";

$settings2 = array();
$result = dbquery("SELECT settings_name, settings_value FROM ".DB_SETTINGS);
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}


if ($_GET['page'] == 1) {

if (isset($_GET['action']) && $_GET['action'] == "count_posts") {
	$result = dbquery("SELECT post_author, COUNT(post_id) as num_posts FROM ".DB_POSTS." GROUP BY post_author");
	$recounter = "0";
	if (dbrows($result)) {
		opentable($locale['523']);
		while ($data = dbarray($result)) {
			#echo "USER: ".$data['post_author']." - COUNT: ".$data['num_posts']."<br />";
			$result2 = dbquery("UPDATE ".DB_USERS." SET user_posts='".$data['num_posts']."' WHERE user_id='".$data['post_author']."'");
			$recounter++;
		}
		echo sprintf($locale['524a'], $recounter);
		closetable();
	log_admin_action("admin-4", "admin_settings_forum_recount"); // Log Admin's Action
	}
}

if (isset($_POST['savesettings'])) {
	$error = 0;
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		if(!set_mainsetting('numofthreads', isnum($_POST['numofthreads']) ? $_POST['numofthreads'] : "5")) { $error = 1; }
		if(!set_mainsetting('forum_ips', isnum($_POST['forum_ips']) ? $_POST['forum_ips'] : nSUPERADMIN)) { $error = 1; }
		if(!set_mainsetting('attachmax', isnum($_POST['attachmax']) ? $_POST['attachmax'] : "150000")) { $error = 1; }
		if(!set_mainsetting('attachtypes', stripinput($_POST['attachtypes']))) { $error = 1; }
		if(!set_mainsetting('attachmentsmax_files', isnum($_POST['attachmentsmax_files']) ? $_POST['attachmentsmax_files'] : "10")) { $error = 1; }
		if(!set_mainsetting('thread_notify', isnum($_POST['thread_notify']) ? $_POST['thread_notify'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_ranks', isnum($_POST['forum_ranks']) ? $_POST['forum_ranks'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_ratings', isnum($_POST['forum_ratings']) ? $_POST['forum_ratings'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_report', isnum($_POST['forum_report']) ? $_POST['forum_report'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_thread_description', isnum($_POST['forum_thread_description']) ? $_POST['forum_thread_description'] : "0")) { $error = 1;}
		if(!set_mainsetting('forum_edit_lock', isnum($_POST['forum_edit_lock']) ? $_POST['forum_edit_lock'] : "0")) { $error = 1; }
		if(!set_mainsetting('popular_threads_timeframe', isnum($_POST['popular_threads_timeframe']) ? $_POST['popular_threads_timeframe'] : 604800)) { $error = 1; }
		if(!set_mainsetting('forum_double_post_merger', isnum($_POST['forum_double_post']) ? $_POST['forum_double_post'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_similar_threads', isnum($_POST['forum_similar_threads']) ? $_POST['forum_similar_threads'] : "0")) { $error = 1; }
		log_admin_action("admin-4", "admin_settings_forum_save");
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		redirect(FUSION_SELF.$aidlink."&error=".$error, true);
	} else {
		redirect(FUSION_SELF.$aidlink."&error=2");
	}
}

opentable($locale['540']);

echo $navigation;

echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' class='center' style='width:70%; max-width:500px;'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['505']."<br /><span class='small2'>".$locale['506']."</span></td>\n";
echo "<td width='50%' class='tbl'><select name='numofthreads' class='textbox'>\n";
echo "<option".($settings2['numofthreads'] == 5 ? " selected='selected'" : "").">5</option>\n";
echo "<option".($settings2['numofthreads'] == 10 ? " selected='selected'" : "").">10</option>\n";
echo "<option".($settings2['numofthreads'] == 15 ? " selected='selected'" : "").">15</option>\n";
echo "<option".($settings2['numofthreads'] == 20 ? " selected='selected'" : "").">20</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['507']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_ips' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_ips'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_ips'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['508']."<br /><span class='small2'>".$locale['509']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='attachmax' value='".$settings2['attachmax']."' maxlength='150' class='textbox' style='width:100px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['510']."<br /><span class='small2'>".$locale['511']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='attachtypes' value='".$settings2['attachtypes']."' maxlength='150' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['511a']."</td>\n";
echo "<td width='50%' class='tbl'><select name='attachmentsmax_files' class='textbox'>\n";
echo "<option".($settings2['attachmentsmax_files'] == 1 ? " selected='selected'" : "").">1</option>\n";
echo "<option".($settings2['attachmentsmax_files'] == 2 ? " selected='selected'" : "").">2</option>\n";
echo "<option".($settings2['attachmentsmax_files'] == 3 ? " selected='selected'" : "").">3</option>\n";
echo "<option".($settings2['attachmentsmax_files'] == 4 ? " selected='selected'" : "").">4</option>\n";
echo "<option".($settings2['attachmentsmax_files'] == 5 ? " selected='selected'" : "").">5</option>\n";
echo "<option".($settings2['attachmentsmax_files'] == 6 ? " selected='selected'" : "").">6</option>\n";
echo "<option".($settings2['attachmentsmax_files'] == 7 ? " selected='selected'" : "").">7</option>\n";
echo "<option".($settings2['attachmentsmax_files'] == 8 ? " selected='selected'" : "").">8</option>\n";
echo "<option".($settings2['attachmentsmax_files'] == 9 ? " selected='selected'" : "").">9</option>\n";
echo "<option".($settings2['attachmentsmax_files'] == 10 ? " selected='selected'" : "").">10</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['512']."</td>\n";
echo "<td width='50%' class='tbl'><select name='thread_notify' class='textbox'>\n";
echo "<option value='1'".($settings2['thread_notify'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['thread_notify'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['520']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_ranks' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_ranks'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_ranks'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['539']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_ratings' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_ratings'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_ratings'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['547']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_report' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_report'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_report'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['521a']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_thread_description' class='textbox'>\n"; // Pimped
echo "<option value='1'".($settings2['forum_thread_description'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_thread_description'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['521']."<br /><span class='small2'>".$locale['522']."</span></td>\n";
echo "<td width='50%' class='tbl'><select name='forum_edit_lock' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_edit_lock'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_edit_lock'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['525']."<br /><span class='small2'>".$locale['526']."</span></td>\n";
echo "<td width='50%' class='tbl'><select name='popular_threads_timeframe' class='textbox'>\n";
echo "<option value='604800'".($settings2['popular_threads_timeframe'] == "604800" ? " selected='selected'" : "").">".$locale['527']."</option>\n";
echo "<option value='2419200'".($settings2['popular_threads_timeframe'] == "2419200" ? " selected='selected'" : "").">".$locale['528']."</option>\n";
echo "<option value='31557600'".($settings2['popular_threads_timeframe'] == "31557600" ? " selected='selected'" : "").">".$locale['529']."</option>\n";
echo "<option value='0'".($settings2['popular_threads_timeframe'] == "0" ? " selected='selected'" : "").">".$locale['530']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['531']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_double_post' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_double_post_merger'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_double_post_merger'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['536']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_similar_threads' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_similar_threads'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_similar_threads'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
	echo "<td class='tbl'>".$locale['853']."</td>\n";
	echo "<td class='tbl'><input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n";
	echo "</tr>\n<tr>\n";
}
echo "<td align='center' colspan='2' class='tbl'><br /><a href='".FUSION_SELF.$aidlink."&amp;action=count_posts'>".$locale['523']."</a>".(isset($_GET['action']) && $_GET['action'] == "count_posts" ? " ".$locale['524'] : "")."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

} elseif ($_GET['page'] == 2) {
// Page 2

if (isset($_POST['savesettings'])) {
	$error = 0;
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		if(!set_mainsetting('forum_statistics_topposters', isnum($_POST['statistics_topposters']) ? $_POST['statistics_topposters'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_statistics_userstats', isnum($_POST['statistics_userstats']) ? $_POST['statistics_userstats'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_statistics_forumstats', isnum($_POST['statistics_forumstats']) ? $_POST['statistics_forumstats'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_statistics_todayonline', isnum($_POST['statistics_todayonline']) ? $_POST['statistics_todayonline'] : "0")) { $error = 1;}
		if(!set_mainsetting('forum_statistics_birthday', isnum($_POST['statistics_birthday']) ? $_POST['statistics_birthday'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_cat_icons', isnum($_POST['forum_cat_icons']) ? $_POST['forum_cat_icons'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_profile_statistics', isnum($_POST['profile_statistics']) ? $_POST['profile_statistics'] : "0")) { $error = 1; }
		log_admin_action("admin-4", "admin_settings_forum_save");
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		redirect(FUSION_SELF.$aidlink."&amp;page=2&error=".$error, true);
	} else {
		redirect(FUSION_SELF.$aidlink."&amp;page=2&amp;error=2");
	}
}

opentable($locale['542']);
echo $navigation;

echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."&amp;page=2'>\n";
echo "<table cellpadding='0' cellspacing='0' class='center' style='width:70%; max-width:500px;'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['534']."</td>\n";
echo "<td width='50%' class='tbl'><select name='statistics_forumstats' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_statistics_forumstats'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_statistics_forumstats'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['532']."</td>\n";
echo "<td width='50%' class='tbl'><select name='statistics_topposters' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_statistics_topposters'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_statistics_topposters'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['533']."</td>\n";
echo "<td width='50%' class='tbl'><select name='statistics_userstats' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_statistics_userstats'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_statistics_userstats'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['537']."</td>\n";
echo "<td width='50%' class='tbl'><select name='statistics_todayonline' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_statistics_todayonline'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_statistics_todayonline'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['548']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_cat_icons' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_cat_icons'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_cat_icons'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['538']."</td>\n";
echo "<td width='50%' class='tbl'><select name='statistics_birthday' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_statistics_birthday'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_statistics_birthday'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['535']."</td>\n";
echo "<td width='50%' class='tbl'><select name='profile_statistics' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_profile_statistics'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_profile_statistics'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
	echo "<td class='tbl'>".$locale['853']."</td>\n";
	echo "<td class='tbl'><input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n";
	echo "</tr>\n<tr>\n";
}
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

} elseif ($_GET['page'] == 3) {
// Page 3

if (isset($_POST['savesettings'])) {
	$_POST['forum_show_onoff_color_on'] = isset($_POST['forum_show_onoff_color_on']) ? $_POST['forum_show_onoff_color_on'] : $settings['forum_show_onoff_color_on'];
	$_POST['forum_show_onoff_color_re'] = isset($_POST['forum_show_onoff_color_re']) ? $_POST['forum_show_onoff_color_re'] : $settings['forum_show_onoff_color_re'];
	$_POST['forum_show_onoff_color_off'] = isset($_POST['forum_show_onoff_color_off']) ? $_POST['forum_show_onoff_color_off'] : $settings['forum_show_onoff_color_off'];
	$error = 0;
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		if(!set_mainsetting('threads_show_next_prev', isnum($_POST['threads_show_next_prev']) ? $_POST['threads_show_next_prev'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_show_loc', isnum($_POST['forum_show_loc']) ? $_POST['forum_show_loc'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_show_age', isnum($_POST['forum_show_age']) ? $_POST['forum_show_age'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_show_sex', isnum($_POST['forum_show_sex']) ? $_POST['forum_show_sex'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_show_onoff', isnum($_POST['forum_show_onoff']) ? $_POST['forum_show_onoff'] : "0")) { $error = 1; }
		if(!set_mainsetting('forum_show_onoff_color_on', preg_match("/([0-9A-F]){6}/i",$_POST['forum_show_onoff_color_on']) ? $_POST['forum_show_onoff_color_on'] : "FFFFFF")) { $error = 1; }
		if(!set_mainsetting('forum_show_onoff_color_re', preg_match("/([0-9A-F]){6}/i",$_POST['forum_show_onoff_color_re']) ? $_POST['forum_show_onoff_color_re'] : "FFFFFF")) { $error = 1; }
		if(!set_mainsetting('forum_show_onoff_color_off', preg_match("/([0-9A-F]){6}/i",$_POST['forum_show_onoff_color_off']) ? $_POST['forum_show_onoff_color_off'] : "FFFFFF")) { $error = 1; }
		log_admin_action("admin-4", "admin_settings_forum_save");
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		redirect(FUSION_SELF.$aidlink."&amp;page=3&error=".$error, true);
	} else {
		redirect(FUSION_SELF.$aidlink."&amp;page=3&amp;error=2");
	}
}

opentable($locale['542']);
echo $navigation;

echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."&amp;page=3'>\n";
echo "<table cellpadding='0' cellspacing='0' class='center' style='width:70%; max-width:500px;'>\n<tr>\n";
echo "<td width='50%' class='tbl'>"."Show next/prev Thread?"."</td>\n"; // localize
echo "<td width='50%' class='tbl'><select name='threads_show_next_prev' class='textbox'>\n";
echo "<option value='1'".($settings2['threads_show_next_prev'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['threads_show_next_prev'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['543']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_show_loc' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_show_loc'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_show_loc'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['544']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_show_age' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_show_age'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_show_age'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['545']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_show_sex' class='textbox'>\n";
echo "<option value='1'".($settings2['forum_show_sex'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_show_sex'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['546']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forum_show_onoff' class='textbox' onchange=\"Color_Forum_Status_Show_Onoff(this);\">\n";
echo "<option value='1'".($settings2['forum_show_onoff'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['forum_show_onoff'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'><div style='float:left'>".$locale['549a']."<br /><span class='small'>".$locale['546']."</span></div>";
echo "<div id='preview_forum_show_onoff_color_on' style='float:right;width:12px;height:12px;border:1px solid black;background-color:#".$settings['forum_show_onoff_color_on'].";'>&nbsp;</div></td>\n";
echo "<td width='50%' class='tbl'>".color_mapper("forum_show_onoff_color_on", $settings['forum_show_onoff_color_on'], $settings['forum_show_onoff'])."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'><div style='float:left'>".$locale['549b']."<br /><span class='small'>".$locale['546']."</span></div>";
echo "<div id='preview_forum_show_onoff_color_re' style='float:right;width:12px;height:12px;border:1px solid black;background-color:#".$settings['forum_show_onoff_color_re'].";'>&nbsp;</div></td>\n";
echo "<td width='50%' class='tbl'>".color_mapper("forum_show_onoff_color_re", $settings['forum_show_onoff_color_re'], $settings['forum_show_onoff'])."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'><div style='float:left'>".$locale['549c']."<br /><span class='small'>".$locale['546']."</span></div>";
echo "<div id='preview_forum_show_onoff_color_off' style='float:right;width:12px;height:12px;border:1px solid black;background-color:#".$settings['forum_show_onoff_color_off'].";'>&nbsp;</div></td>\n";
echo "<td width='50%' class='tbl'>".color_mapper("forum_show_onoff_color_off", $settings['forum_show_onoff_color_off'], $settings['forum_show_onoff'])."</td>\n";
echo "</tr>\n<tr>\n";
if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
	echo "<td class='tbl'>".$locale['853']."</td>\n";
	echo "<td class='tbl'><input type='password' name='admin_password' value='".(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")."' class='textbox' style='width:150px;' /></td>\n";
	echo "</tr>\n<tr>\n";
}
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
echo "<script type='text/javascript'>
function Color_Forum_Status_Show_Onoff(phtomrk) {
	if (phtomrk.value == 0) {
		document.forms['settingsform'].forum_show_onoff_color_on.disabled = true;
		document.forms['settingsform'].forum_show_onoff_color_re.disabled = true;
		document.forms['settingsform'].forum_show_onoff_color_off.disabled = true;
	} else {
		document.forms['settingsform'].forum_show_onoff_color_on.disabled = false;
		document.forms['settingsform'].forum_show_onoff_color_re.disabled = false;
		document.forms['settingsform'].forum_show_onoff_color_off.disabled = false;
	}
}
</script>\n";
closetable();

}

require_once TEMPLATES."footer.php";
?>