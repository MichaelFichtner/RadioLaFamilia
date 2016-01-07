<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: my_threads.php
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
require_once "../../maincore.php";
require_once TEMPLATES."header.php";

if (!iMEMBER) { redirect("../../index.php"); }

add_to_title($locale['global_200'].$locale['global_041']);

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }

$rows = dbrows(dbquery(
	"SELECT tt.thread_id
	FROM ".DB_THREADS." tt
	INNER JOIN ".DB_FORUMS." tf ON tt.forum_id = tf.forum_id
	WHERE ".groupaccess('tf.forum_access')." AND tt.thread_author='".$userdata['user_id']."' AND tt.thread_hidden='0' LIMIT 100"
));

if(IF_MULTI_LANGUAGE_FORUM && iMEMBER && isset($userdata['user_forumpanellocale']) && $userdata['user_forumpanellocale'] != '') {
	$insert = $userdata['user_forumpanellocale'] == "all" ? "" : " AND tc.forum_language='".$userdata['user_forumpanellocale']."' ";
} else {
	$insert = "";
}

if ($rows) {
	if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
	$result = dbquery(
		"SELECT tt.forum_id, tt.thread_id, tt.thread_subject, tt.thread_views, tt.thread_lastuser,
		tt.thread_lastpost, tt.thread_postcount, tf.forum_name, tf.forum_access,
		tc.forum_language,
		tu.user_id, tu.user_name, tu.user_status
		FROM ".DB_THREADS." tt
		INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
		INNER JOIN ".DB_FORUMS." tc ON tf.forum_cat=tc.forum_id
		INNER JOIN ".DB_USERS." tu ON tt.thread_lastuser = tu.user_id
		WHERE ".groupaccess('tf.forum_access')." AND tt.thread_author = '".$userdata['user_id']."'".$insert." AND tt.thread_hidden='0'
		ORDER BY tt.thread_lastpost DESC LIMIT ".(int)$_GET['rowstart'].",20"
	);
	$i = 0;
	opentable($locale['global_041']);
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
	echo "<td class='tbl2'>&nbsp;</td>\n";
	echo "<td width='100%' class='tbl2'><strong>".$locale['global_044']."</strong></td>\n";
	echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_045']."</strong></td>\n";
	echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_046']."</strong></td>\n";
	if(IF_MULTI_LANGUAGE_FORUM) echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_061']."</strong></td>\n"; // Pimped
	echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_047']."</strong></td>\n";
	echo "</tr>\n";
	while ($data = dbarray($result)) {
		if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
		echo "<tr>\n";
		echo "<td class='".$row_color."'>";
		if ($data['thread_lastpost'] > $lastvisited) {
			$thread_match = $data['thread_id']."\|".$data['thread_lastpost']."\|".$data['forum_id'];
			if (iMEMBER && preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
				echo "<img src='".get_image("folder")."' alt='' />";
			} else {
				echo "<img src='".get_image("foldernew")."' alt='' />";
			}
		} else {
			echo "<img src='".get_image("folder")."' alt='' />";
		}
		echo "</td>\n";
		echo "<td width='100%' class='".$row_color."'><a href='".make_url(FORUM."viewthread.php?thread_id=".$data['thread_id'], BASEDIR.SEO_F_THREAD_A.SEO_F_THREAD_B1.$data['thread_id'].SEO_F_THREAD_B2, $data['thread_subject'], SEO_F_THREAD_C)."' title='".$data['thread_subject']."'>".trimlink($data['thread_subject'], 30)."</a><br />\n".$data['forum_name']."</td>\n"; // Pimped
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".$data['thread_views']."</td>\n";
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".($data['thread_postcount']-1)."</td>\n";
		if(IF_MULTI_LANGUAGE_FORUM) echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".get_image($data['forum_language'], $data['forum_language'], "", $data['forum_language'], "", true)."</td>\n"; // Pimped
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".profile_link($data['thread_lastuser'], $data['user_name'], $data['user_status'])."<br />\n".showdate("forumdate", $data['thread_lastpost'])."</td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table>\n";
	require_once INFUSIONS."forum_threads_list_panel/threads_list_navigation.php";
	closetable();
	if ($rows > 20) { echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 20, $rows, 3)."\n</div>\n"; }
} else {
	opentable($locale['global_041']);
	echo "<div style='text-align:center'><br />\n".$locale['global_053']."<br /><br />\n</div>\n";
	require_once INFUSIONS."forum_threads_list_panel/threads_list_navigation.php";
	closetable();
}

require_once TEMPLATES."footer.php";
?>