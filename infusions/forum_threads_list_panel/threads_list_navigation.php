<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: threads_list_navigation.php
| Version: Pimped Fusion v0.05.00
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
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

	if (iMEMBER) {
		echo "<div class='tbl1' style='text-align:center'><a href='".INFUSIONS."forum_threads_list_panel/my_threads.php'>".$locale['global_041']."</a> ::\n";
		echo "<a href='".INFUSIONS."forum_threads_list_panel/my_posts.php'>".$locale['global_042']."</a> ::\n";
		echo "<a href='".INFUSIONS."forum_threads_list_panel/new_posts.php'>".$locale['global_043']."</a>";
		if($settings['thread_notify']) {
			echo " ::\n<a href='".INFUSIONS."forum_threads_list_panel/my_tracked_threads.php'>".$locale['global_056']."</a>";
		}
		if(IF_MULTI_LANGUAGE_FORUM && isset($userdata['user_forumpanellocale'])) {
			echo " ::\n<a href='".INFUSIONS."forum_threads_list_panel/settings.php'>Settings</a>";
		}
		echo "</div>\n";
	}


?>