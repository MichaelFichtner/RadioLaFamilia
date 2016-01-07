<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum_thread_list_panel.php
| Version: Pimped Fusion v0.08.00
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

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }

$display_more = 10;

add_to_head("<script type='text/javascript'>
<!--
function show_more_threads() {
	var smt = document.getElementById('show_more_threads');
	var smttxt = document.getElementById('show_more_threads_text');
	if (smt.style.display == 'none') {
	smt.style.display = 'block';
	smttxt.innerHTML = '".$locale['global_063']."';
	} else {
	smt.style.display = 'none';
	smttxt.innerHTML = '".$locale['global_062']."';
	}
}
//-->
</script>");

// Add Tooltip-JavaScript
add_tooltip();

#$data = dbarray(dbquery("SELECT tt.thread_lastpost
#	FROM ".DB_FORUMS." tf
#	INNER JOIN ".DB_THREADS." tt ON tf.forum_id = tt.forum_id
#	WHERE ".groupaccess('tf.forum_access')."
#	ORDER BY tt.thread_lastpost DESC LIMIT ".($settings['numofthreads']-1).", ".$settings['numofthreads']));
#
#$timeframe = empty($data['thread_lastpost']) ? 0 : $data['thread_lastpost'];
#
#What is this $timeframe for?

if(IF_MULTI_LANGUAGE_FORUM && iMEMBER && isset($userdata['user_forumpanellocale']) && $userdata['user_forumpanellocale'] != '') {
	$insert = $userdata['user_forumpanellocale'] == "all" ? "" : " AND tc.forum_language='".$userdata['user_forumpanellocale']."' ";
} else {
	$insert = "";
}

$result = dbquery(
	"SELECT tt.thread_id, tt.thread_subject, tt.thread_views, tt.thread_lastuser, tt.thread_lastpost,
	tt.thread_poll, tt.thread_lastpostid, tt.thread_postcount, tt.thread_resolved,
	tu.user_id, tu.user_name, tu.user_status,
	tp.post_message, tp.post_smileys,
	tf.forum_id, tf.forum_name, tf.forum_access, tf.forum_markresolved,
	tc.forum_language
	FROM ".DB_THREADS." tt
	INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
	INNER JOIN ".DB_FORUMS." tc ON tf.forum_cat=tc.forum_id
	INNER JOIN ".DB_POSTS." tp ON tt.thread_lastpostid=tp.post_id
	INNER JOIN ".DB_USERS." tu ON tt.thread_lastuser=tu.user_id
	WHERE ".groupaccess('tf.forum_access').$insert." AND thread_hidden='0' 
	ORDER BY tt.thread_lastpost DESC LIMIT 0,".($settings['numofthreads'] + $display_more)
);#AND tt.thread_lastpost >= ".$timeframe."

if (dbrows($result)) {
	$i = 0;
	opentable($locale['global_040']);
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
	echo "<td class='tbl2'>&nbsp;</td>\n";
	echo "<td width='100%' class='tbl2'><strong>".$locale['global_044']."</strong></td>\n";
	echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_045']."</strong></td>\n";
	echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_046']."</strong></td>\n";
	if(IF_MULTI_LANGUAGE_FORUM) echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_061']."</strong></td>\n"; // Pimped
	echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_047']."</strong></td>\n";
	echo "</tr>\n";
	while ($data = dbarray($result)) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");

		if($i == $settings['numofthreads']) {
			echo "</table>\n";
			echo "<div align='left'>\n<br /><img src='".INFUSIONS."forum_threads_list_panel/images/display_more.gif' alt='' />&nbsp;<a href=\"javascript:void(0)\" onclick=\"show_more_threads();\"><span id='show_more_threads_text'>".$locale['global_062']."</span></a>\n</div>\n";
			echo "<div id='show_more_threads' style='display: none;'><br />\n";
			echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
			echo "<td class='tbl2'>&nbsp;</td>\n";
			echo "<td width='100%' class='tbl2'><strong>".$locale['global_044']."</strong></td>\n";
			echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_045']."</strong></td>\n";
			echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_046']."</strong></td>\n";
			if(IF_MULTI_LANGUAGE_FORUM) echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_061']."</strong></td>\n"; // Pimped
			echo "<td width='1%' class='tbl2' style='text-align:center;white-space:nowrap'><strong>".$locale['global_047']."</strong></td>\n";
			echo "</tr>\n";
		}
		echo "<tr>\n<td class='".$row_color."'>";
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
		if ($data['thread_poll']) {
			$thread_poll = "<span class='small' style='font-weight:bold'>[".$locale['global_051']."]</span> ";
		} else {
			$thread_poll = "";
		}
		
		$message = $data['post_message'];
		
		 // try to fix an unknown bug: div's are not shown
		
		#$qcount = substr_count($message, "[quote]");
		#for ($q=0;$q < $qcount;$q++) $message = preg_replace('#\[quote\](.*?)\[/quote\]#si', '', $message); // problem with this: there are too much possible bb-codes
		
		#$code_count = substr_count($message, "[code]");
		#for ($q=0; $q < $code_count; $q++) $message = preg_replace("#\[code\](.*?)\[/code\]#sie", "", $message, 1);
		
		$message = parseubb(nl2br(trimlink($message, 300)));
		if ($data['post_smileys']) { $message = parsesmileys($message); }
		$message = phpentities(str_replace(array("[","]"),array("&#91;","&#93;"), $message));
		#$message = str_replace(array("&lt;div", "&lt;/div"), array("&lt;span", "&lt;/span"), $message); // does not show the thing correct
		
		$div_count = substr_count($message, "&lt;div");
		for ($q=0; $q < $div_count; $q++) $message = preg_replace('#&lt;div(.*?)&gt;(.*?)&lt;/div&gt;#si', '', $message, 1); // this is the best solution I could find
		
		echo "</td>\n";
		echo "<td width='100%' class='".$row_color."'>".$thread_poll."<a href='".make_url(FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['thread_lastpostid'], BASEDIR."forum-thread-".$data['thread_id']."-pid".$data['thread_lastpostid']."-", $data['thread_subject'], ".html")."#post_".$data['thread_lastpostid']."' title='&lt;strong&gt;".$data['thread_subject']."&lt;/strong&gt;&lt;br/&gt;".$message."' class='tooltip'>".trimlink($data['thread_subject'], 30)."</a>".($data['forum_markresolved'] && $data['thread_resolved'] ? $locale['global_067'] : "")."<br />\n
		<span class='small2'>".$locale['global_048'].": <a href='".make_url(FORUM."viewforum.php?forum_id=".$data['forum_id'], BASEDIR."forum-".$data['forum_id']."-", $data['forum_name'], ".html")."' title='".$data['forum_name']."'>".$data['forum_name']."</a></span></td>\n"; // Pimped: make_url
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".$data['thread_views']."</td>\n";
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".($data['thread_postcount']-1)."</td>\n";
		if(IF_MULTI_LANGUAGE_FORUM) echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".get_image($data['forum_language'], $data['forum_language'], "", $data['forum_language'], "", true)."</td>\n"; // Pimped
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".profile_link($data['thread_lastuser'], $data['user_name'], $data['user_status'])."<br />\n".showdate("forumdate", $data['thread_lastpost'])."</td>\n";
		echo "</tr>\n";
		$i++;
	}
	if($i > $settings['numofthreads']) { 
		echo "</table></div><br />";
	} else { 
		echo "</table><br />\n"; 
	}

	require_once INFUSIONS."forum_threads_list_panel/threads_list_navigation.php";

	closetable();
}
?>