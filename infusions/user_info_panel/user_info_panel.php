<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: user_info_panel.php
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
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if (iMEMBER) {
	openside($userdata['user_name']);
	$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");
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
	if ($msg_count) { echo "<br /><br /><div style='text-align:center'><strong><a href='".BASEDIR.make_url("messages.php", SEO_MESSAGE_A, "", SEO_MESSAGE_C)."' class='side'>".sprintf($locale['global_125'], $msg_count).($msg_count == 1 ? $locale['global_126'] : $locale['global_127'])."</a></strong></div>\n"; }
} else {
	openside($locale['global_100']);
	echo "<div style='text-align:center'>\n";
	echo "<form name='loginform' method='post' action='".$_SERVER['PHP_SELF'].(FUSION_QUERY ? "?".FUSION_QUERY : "")."'>"; // Pimped
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