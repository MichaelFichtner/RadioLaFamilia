<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: reported.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter, Keddy
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
require_once "../maincore.php";
require_once TEMPLATES."header.php";
include LOCALE.LOCALESET."admin/reported.php";

if ((!iADMIN && !iMODERATOR) || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("index.php"); }

if ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['report_id']) && isnum($_GET['report_id']))) {
	$result = dbquery("DELETE FROM ".DB_FORUM_REPORT." WHERE report_id='".(int)$_GET['report_id']."'");
}

$result = dbquery("SELECT r.report_id, r.report_post, r.report_user, r.report_datestamp, p.thread_id, u.user_id, u.user_name, t.thread_id, t.thread_subject
	FROM ".DB_FORUM_REPORT." r
	LEFT JOIN ".DB_POSTS." p ON r.report_post=p.post_id 
	LEFT JOIN ".DB_THREADS." t ON p.thread_id=t.thread_id
	LEFT JOIN ".DB_USERS." u ON r.report_user=u.user_id
	");

opentable($locale['400']);

if (dbrows($result) != 0) {
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border' style='margin-bottom:5px'>\n<tr>\n";
	echo "<td align='center' class='tbl2' width='25%'>".$locale['401']."</td>\n";
	echo "<td align='center' class='tbl2' width='25%'>".$locale['402']."</td>\n";
	echo "<td align='center' class='tbl2' width='25%'>".$locale['403']."</td>\n";
	echo "<td align='center' class='tbl2' width='25%'>".$locale['404']."</td>\n</tr>\n";

	while($data = dbarray($result)) {
		echo "<tr>\n<td align='center' class='tbl2' width='25%'>";
		echo "<a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."#post_".$data['report_post']."'>".$data['thread_subject']."</a>";
		echo "</td>\n";
		echo "<td align='center' class='tbl2' width='25%'>".profile_link($data['user_id'], $data['user_name'], "0", "profile-link", "", "", "")."</td>\n";
		echo "<td align='center' class='tbl2' width='25%'>".showdate("forumdate", $data['report_datestamp'])."</td>\n";
		echo "<td align='center' class='tbl2' width='25%'>";
		echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;report_id=".$data['report_id']."' onclick=\"return confirm('".$locale['406']."');\">".$locale['405']."</a>";
		echo "</td>\n";
	}
	echo "</tr>\n</table>\n";

	} else {
		echo "<div style='text-align:center'><br />".$locale['407']."<br /><br /></div>\n";
	}

closetable();
	
require_once TEMPLATES."footer.php";
?>