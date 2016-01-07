<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: login.php
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
require_once "maincore.php";
require_once TEMPLATES."header.php";

add_to_title($locale['global_200'].$locale['global_100']);

if (iMEMBER) {
	opentable($userdata['user_name']);
	echo "<div style='text-align:center'><br />\n";
	$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0'AND message_folder='0'");
	echo THEME_BULLET." <a href='".BASEDIR.make_url("edit_profile.php", "edit_profile", "", ".html")."' class='side'>".$locale['global_120']."</a><br />\n";
	echo THEME_BULLET." <a href='".BASEDIR.make_url("messages.php", "messages", "", ".html")."' class='side'>".$locale['global_121']."</a><br />\n";
	echo THEME_BULLET." <a href='".BASEDIR.make_url("members.php", "members", "", ".html")."' class='side'>".$locale['global_122']."</a><br />\n";
	if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
		echo THEME_BULLET." <a href='".ADMIN."index.php".$aidlink."' class='side'>".$locale['global_123']."</a><br />\n";
	}
	echo THEME_BULLET." <a href='".BASEDIR.make_url("setuser.php?logout=yes", "logout", "", ".html")."' class='side'>".$locale['global_124']."</a>\n";
	if ($msg_count) { echo "<br /><br /><strong><a href='".BASEDIR.make_url("messages.php", "messages", "", ".html")."' class='side'>".sprintf($locale['global_125'], $msg_count).($msg_count == 1 ? $locale['global_126'] : $locale['global_127'])."</a></strong>\n"; }
	echo "<br /><br /></div>\n";
} else {
	opentable($locale['global_100']);
	echo "<div style='text-align:center'><br />\n";
	echo "<form name='loginform' method='post' action='".make_url("login.php", "login", "", ".html")."'>\n";
	echo $locale['global_101']."<br />\n<input type='text' name='user_name' class='textbox' style='width:100px' /><br />\n";
	echo $locale['global_102']."<br />\n<input type='password' name='user_pass' class='textbox' style='width:100px' /><br />\n";
	echo "<label><input type='checkbox' name='remember_me' value='y' />".$locale['global_103']."</label><br /><br />\n";
	echo "<input type='submit' name='login' value='".$locale['global_104']."' class='button' /><br />\n";
	echo "<br /></form>\n";
	if ($settings['enable_registration']) {
		echo "".$locale['global_105']."<br /><br />\n";
	}
	echo $locale['global_106'];
	echo "<br /><br /></div>\n";
}
closetable();

require_once TEMPLATES."footer.php";
?>