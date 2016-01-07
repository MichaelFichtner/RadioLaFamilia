<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/includes/forum_statistics.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../../maincore.php";
require_once TEMPLATES."window_header.php";
include_once LOCALE.LOCALESET."forum/main.php";

if (iMEMBER && $settings['forum_report'] && isset($_GET['post_id']) && isnum($_GET['post_id'])) {
	global $userdata;
		if (!dbcount("(report_post)", DB_FORUM_REPORT, "report_post='".$_GET['post_id']."'")) {
			if(isset($_POST['report'])) {
				$result = dbquery("INSERT INTO ".DB_FORUM_REPORT." (report_user, report_post, report_datestamp) VALUES ('".$userdata['user_id']."', '".$_GET['post_id']."', '".time()."')");
            echo "<br />".$locale['582']."<br /><a href='javascript:window.close();'>".$locale['581']."</a>";
			} else {
				echo "<br /><form name='reportform' method='post' action='".FUSION_SELF."?post_id=".$_GET['post_id']."'>
				<input type='submit' name='report' value='".$locale['584']."' class='button' />
				</form>";
			}
        } else {
			echo "<br />".$locale['583']."<br /><a href='javascript:window.close();'>".$locale['581']."</a>";
		}
}
echo "<br /><br /><br />";
require_once TEMPLATES."window_footer.php";
?>