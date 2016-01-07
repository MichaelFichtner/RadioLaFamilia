<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: my_posts.php
| Version: Pimped Fusion v0.08.01
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

add_to_title($locale['global_200'].$locale['global_042']);

$result = dbquery(
	"SELECT tp.post_id
	FROM ".DB_POSTS." tp
	INNER JOIN ".DB_THREADS." tt ON tp.thread_id = tt.thread_id
	INNER JOIN ".DB_FORUMS." tf ON tp.forum_id = tf.forum_id
	WHERE ".groupaccess('forum_access')." AND post_author='".$userdata['user_id']."' AND post_hidden='0' AND thread_hidden='0'
");

if(IF_MULTI_LANGUAGE_FORUM && iMEMBER && isset($userdata['user_forumpanellocale']) && $userdata['user_forumpanellocale'] != '') {
	$insert = $userdata['user_forumpanellocale'] == "all" ? "" : " AND tc.forum_language='".$userdata['user_forumpanellocale']."' ";
} else {
	$insert = "";
}

$rows = dbrows($result);
if ($rows) {
	if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
	$result = dbquery(
		"SELECT tp.forum_id, tp.thread_id, tp.post_id, tp.post_author, tp.post_datestamp,
		tf.forum_name, tf.forum_access, tt.thread_subject,
		tc.forum_language
		FROM ".DB_POSTS." tp
		INNER JOIN ".DB_FORUMS." tf ON tp.forum_id=tf.forum_id
		INNER JOIN ".DB_FORUMS." tc ON tf.forum_cat=tc.forum_id
		INNER JOIN ".DB_THREADS." tt ON tp.thread_id=tt.thread_id
		WHERE ".groupaccess('tf.forum_access')." AND tp.post_author='".$userdata['user_id']."'".$insert." AND post_hidden='0' AND thread_hidden='0'
		ORDER BY tp.post_datestamp DESC LIMIT ".(int)$_GET['rowstart'].",20"
	);
	$i = 0;
	opentable($locale['global_042']);
	echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>\n<tr>\n";
	echo "<td width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['global_048']."</strong></td>\n";
	echo "<td width='100%' class='tbl2'><strong>".$locale['global_044']."</strong></td>\n";
	if(IF_MULTI_LANGUAGE_FORUM) echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_061']."</strong></td>\n"; // Pimped
	echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_049']."</strong></td>\n";
	echo "</tr>\n";
	while ($data = dbarray($result)) {
		if ($i % 2 == 0) { $row_color = "tbl1"; } else { $row_color = "tbl2"; }
		echo "<tr>\n";
		echo "<td width='1%' class='".$row_color."' style='white-space:nowrap'>".trimlink($data['forum_name'], 30)."</td>\n";
		echo "<td width='100%' class='".$row_color."'><a href='".make_url(FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['post_id'], BASEDIR."forum-thread-".$data['thread_id']."-pid".$data['post_id']."-", $data['thread_subject'], ".html")."#post_".$data['post_id']."' title='".$data['thread_subject']."'>".trimlink($data['thread_subject'], 40)."</a></td>\n"; // Pimped
		if(IF_MULTI_LANGUAGE_FORUM) echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".get_image($data['forum_language'], $data['forum_language'], "", $data['forum_language'], "", true)."</td>\n"; // Pimped
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".showdate("forumdate", $data['post_datestamp'])."</td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table>\n";
	require_once INFUSIONS."forum_threads_list_panel/threads_list_navigation.php";
	closetable();
	if ($rows > 20) { echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 20, $rows, 3)."\n</div>\n"; }
} else {
	opentable($locale['global_042']);
	echo "<div style='text-align:center'><br />\n".$locale['global_054']."<br /><br />\n</div>\n";
	require_once INFUSIONS."forum_threads_list_panel/threads_list_navigation.php";
	closetable();
}

require_once TEMPLATES."footer.php";
?>