<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: enhanced_user_info_panel.php
| Version: Pimped Fusion v0.09.01
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if (file_exists(INFUSIONS."enhanced_user_info_panel/locale/".$settings['locale'].".php")) {
	include_once INFUSIONS."enhanced_user_info_panel/locale/".$settings['locale'].".php";
} else {
	include_once INFUSIONS."enhanced_user_info_panel/locale/English.php";
}

add_to_head("<script type='text/javascript' src='".INFUSIONS."enhanced_user_info_panel/eui.js'></script>");

if (iMEMBER) {
	openside($userdata['user_name']);
	if ($userdata['user_avatar'] && file_exists(IMAGES_AVA.$userdata['user_avatar'])) {
		$img = "<img src='".IMAGES_AVA.$userdata['user_avatar']."' alt='".$locale['eui_100']."' title='".$locale['eui_100']."' style='border:0' />";
		echo "<div style='text-align:center;margin:10px 0 10px 0'>".profile_link($userdata['user_id'], $userdata['user_name'], "0", "profile-link", "", "", $img)."</div>\n";
	}
	$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");
	if ($msg_count) {
		echo "<div style='text-align:center'><strong><a href='".BASEDIR.make_url("messages.php", SEO_MESSAGE_A, "", SEO_MESSAGE_C)."' class='side'>".sprintf($locale['global_125'], $msg_count).($msg_count == 1 ? $locale['global_126'] : $locale['global_127'])."</a></strong></div>\n";
	}
	echo "<div class='side-label eui_master_head' style='margin-bottom:1px' title='".$locale['eui_101']."'><a class='eui_head' href='#' onclick='return false;' style='outline:none; text-decoration:none; font-weight: bold;'>".$locale['eui_102']."</a></div>\n";
	echo "<div class='eui_master_body' style='margin-bottom:3px'>\n";
	echo THEME_BULLET." <a href='".BASEDIR.make_url("edit_profile.php", SEO_PROFILE_EDIT_A, "", SEO_PROFILE_EDIT_C)."' class='side'>".$locale['global_120']."</a><br />\n";
	echo THEME_BULLET." <a href='".BASEDIR.make_url("messages.php", SEO_MESSAGE_A, "", SEO_MESSAGE_C)."' class='side'>".$locale['global_121']."</a><br />\n";
	if ($settings['forum_report'] && (iADMIN || iMODERATOR)) {
		$reported = dbcount("(report_post)", DB_FORUM_REPORT);
		echo THEME_BULLET." <a href='".ADMIN."reported.php".$aidlink."' class='side'>".$locale['global_128']."</a> (".$reported.")<br />\n";
	}
	echo THEME_BULLET." <a href='".BASEDIR.make_url("members.php", SEO_MEMBERLIST_A, "", SEO_MEMBERLIST_C)."' class='side'>".$locale['global_122']."</a><br />\n";
	if (iADMIN && ((iUSER_RIGHTS != "" && iUSER_RIGHTS != "C" && iUSER_RIGHTS != "FMD" && iUSER_RIGHTS != "C.FMD")
	|| (iGROUP_RIGHTS != "" && iGROUP_RIGHTS != "C" && iGROUP_RIGHTS != "FMD" && iGROUP_RIGHTS != "C.FMD"))) {
		echo THEME_BULLET." <a href='".ADMIN."index.php".$aidlink."' class='side'>".$locale['global_123']."</a><br />\n";
	}
	echo THEME_BULLET." <a href='".BASEDIR.make_url("setuser.php?logout=yes", SEO_LOGOUT_A, "", SEO_LOGOUT_C)."' class='side'>".$locale['global_124']."</a>\n";
	echo "</div>\n";
	echo "<div class='side-label eui_head' style='margin-bottom:1px' title='".$locale['eui_101']."'><a class='eui_head' href='#' onclick='return false;' style='outline:none; text-decoration:none; font-weight: bold;'>".$locale['eui_103']."</a></div>\n";
	echo "<div class='eui_body' style='margin-bottom:3px'>\n";
	echo THEME_BULLET." <a href='".INFUSIONS."forum_threads_list_panel/my_threads.php' class='side'>".$locale['global_041']."</a><br />\n";
	echo THEME_BULLET." <a href='".INFUSIONS."forum_threads_list_panel/my_posts.php' class='side'>".$locale['global_042']."</a><br />\n";
	echo THEME_BULLET." <a href='".INFUSIONS."forum_threads_list_panel/new_posts.php' class='side'>".$locale['global_043']."</a>";
	if($settings['thread_notify']) {
		echo "<br />\n".THEME_BULLET." <a href='".INFUSIONS."forum_threads_list_panel/my_tracked_threads.php' class='side'>".$locale['global_056']."</a>";
	}
	echo "</div>\n";
	echo "<div class='side-label eui_head' title='".$locale['eui_101']."'><a class='eui_head' href='#' onclick='return false;' style='outline:none; text-decoration:none; font-weight: bold;'>".$locale['eui_104']."</a></div>\n";
	echo "<div class='eui_body'>\n";
	echo THEME_BULLET." <a href='".BASEDIR.make_url("submit.php?stype=l", SEO_SUBMIT_LINK_A, "", SEO_SUBMIT_LINK_C)."' class='side'>".$locale['eui_105']."</a><br />\n";
	echo THEME_BULLET." <a href='".BASEDIR.make_url("submit.php?stype=n", SEO_SUBMIT_NEWS_A, "", SEO_SUBMIT_NEWS_C)."' class='side'>".$locale['eui_106']."</a><br />\n";
	echo THEME_BULLET." <a href='".BASEDIR.make_url("submit.php?stype=a", SEO_SUBMIT_ARTICLE_A, "", SEO_SUBMIT_ARTICLE_C)."' class='side'>".$locale['eui_107']."</a><br />\n";
	echo THEME_BULLET." <a href='".BASEDIR.make_url("submit.php?stype=p", SEO_SUBMIT_PHOTO_A, "", SEO_SUBMIT_PHOTO_C)."' class='side'>".$locale['eui_108']."</a>\n";
	echo "</div>\n";
} else {
	openside($locale['global_100']);
	echo "<div style='text-align:center'>\n";
	echo "<form name='loginform' method='post' action='".$_SERVER['PHP_SELF'].(FUSION_QUERY ? "?".FUSION_QUERY : "")."'>";
	echo $locale['global_101']."<br />\n<input type='text' name='user_name' class='textbox' style='width:100px' /><br />\n";
	echo $locale['global_102']."<br />\n<input type='password' name='user_pass' class='textbox' style='width:100px' /><br />\n";
	echo "<input type='checkbox' name='remember_me' value='y' title='".$locale['global_103']."' style='vertical-align:middle;' />\n";
	echo "<input type='submit' name='login' value='".$locale['global_104']."' class='button' /><br />\n";
	echo "</form>\n<br />\n";
	if ($settings['enable_registration']) {
		echo "".$locale['global_105']."<br /><br />\n";
	}
	echo $locale['global_106']."\n</div>\n";
}
closeside();
?>