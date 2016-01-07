<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/index.php
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
require_once "../maincore.php";
require_once TEMPLATES."header.php";
include LOCALE.LOCALESET."forum/main.php";

// Pimped: Forum Observer
require_once FORUM_INC."forum_observer.php";
whoishere_observer("index");

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }

add_to_title($locale['global_200'].$locale['400']);

opentable($locale['400']);

echo "<!--pre_forum_idx--><table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_idx_table'>\n";

$forum_list = ''; $current_cat = '';
$access = ''; // Pimped: access
$aresult = dbquery(
	"SELECT f.forum_id, f.forum_access
	FROM ".DB_FORUMS." f
	LEFT JOIN ".DB_FORUMS." f2 ON f.forum_cat = f2.forum_id
	WHERE f.forum_cat!='0' AND f.forum_parent='0'".(!(bool)IF_MULTI_LANGUAGE_FORUM ? '':" AND (f2.forum_language='all' OR f2.forum_language='".LANGUAGE."')")."
	GROUP BY forum_id ORDER BY f2.forum_order ASC, f.forum_order ASC"
); 
while ($adata = dbarray($aresult)) {
	if ($access != '') { $access .= "|"; }
	$access .= $adata['forum_access'];
}
$result = dbquery(
	"SELECT f.forum_id, f.forum_name, f.forum_description, f.forum_moderators, f.forum_lastpost, f.forum_image,
	f.forum_threadcount, f.forum_postcount,
	f2.forum_name AS forum_cat_name, u.user_id, u.user_name, u.user_status
	FROM ".DB_FORUMS." f
	LEFT JOIN ".DB_FORUMS." f2 ON f.forum_cat = f2.forum_id
	LEFT JOIN ".DB_USERS." u ON f.forum_lastuser = u.user_id
	WHERE ".groupaccess('f.forum_access', $access, "($access)")." AND f.forum_cat!='0' AND f.forum_parent='0'".(!(bool)IF_MULTI_LANGUAGE_FORUM ? '':" AND (f2.forum_language='all' OR f2.forum_language='".LANGUAGE."')")."
	GROUP BY forum_id ORDER BY f2.forum_order ASC, f.forum_order ASC"
); // Pimped: Sub-Cats, 3rd parameter of groupaccess is only for debuging
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
		if ($data['forum_cat_name'] != $current_cat) {
			$current_cat = $data['forum_cat_name'];
			echo "<tr>\n<td colspan='".($settings['forum_cat_icons'] == "1" ? "3" : "2")."' class='forum-caption forum_cat_name'><!--forum_cat_name-->".$data['forum_cat_name']."</td>\n";
			echo "<td align='center' width='1%' class='forum-caption' style='white-space:nowrap'>".$locale['402']."</td>\n";
			echo "<td align='center' width='1%' class='forum-caption' style='white-space:nowrap'>".$locale['403']."</td>\n";
			echo "<td width='1%' class='forum-caption' style='white-space:nowrap'>".$locale['404']."</td>\n";
			echo "</tr>\n";
		}
		$moderators = '';
		if ($data['forum_moderators']) {
			$mod_groups = explode(".", $data['forum_moderators']);
			foreach ($mod_groups as $mod_group) {
				if ($moderators) $moderators .= ", ";
				$moderators .= $mod_group < nMEMBER ? group_link($mod_group, getgroupname($mod_group)) : getgroupname($mod_group); // Pimped: group_link
			}
		}
		$last_data = dbarray(dbquery("SELECT forum_id, forum_lastpost FROM ".DB_FORUMS." WHERE forum_id = '".$data['forum_id']."' OR forum_parent='".$data['forum_id']."' GROUP BY forum_lastpost DESC")); // Pimped: Sub-Cats
	    $forum_match = "\|".$last_data['forum_lastpost']."\|".$last_data['forum_id']; // Pimped: Sub-Cats
	   if ($last_data['forum_lastpost'] > $lastvisited) { // Pimped: Sub-Cats
			if (iMEMBER && preg_match("({$forum_match}\.|{$forum_match}$)", $userdata['user_threads'])) {
				$fim = "<img src='".get_image("folder")."' alt='".$locale['561']."' />";
			} else {
				$fim = "<img src='".get_image("foldernew")."' alt='".$locale['560']."' />";
			}
		} else {
			$fim = "<img src='".get_image("folder")."' alt='".$locale['561']."' />";
		}
		echo "<tr>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$fim."</td>\n";
		
		if($settings['forum_cat_icons'] == "1") {
			echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>";    
			if ($data['forum_image'] != '' && file_exists(IMAGES_FC.$data['forum_image'])) {
				echo "<img src='".IMAGES_FC.$data['forum_image']."' border='0' alt='' title='".$data['forum_name']."' />";     
			} else {
				echo "<img src='".IMAGES_FC."default.png' border='0' alt='' title='".$data['forum_name']."' />";
			}
			echo"</td>";
		}

		echo "<td class='tbl1 forum_name'><!--forum_name--><a href='".make_url(FORUM."viewforum.php?forum_id=".$data['forum_id'], BASEDIR."forum-".$data['forum_id']."-", $data['forum_name'], ".html")."'>".$data['forum_name']."</a><br />\n"; // Pimped: make_url
		if ($data['forum_description'] || $moderators) {
			echo "<span class='small'>".$data['forum_description'].($data['forum_description'] && $moderators ? "<br />\n" : "");
			echo ($moderators ? "<strong>".$locale['411']."</strong>".$moderators."</span>\n" : "</span>\n")."<br />\n";
		}
		whoishere_show("index", $data['forum_id']); // Pimped: Who is here
		// Pimped --> Sub-Cats	
		$parent_result = dbquery("SELECT forum_id, forum_name, forum_parent FROM ".DB_FORUMS." WHERE ".groupaccess('forum_access')." AND forum_parent='".$data['forum_id']."' ORDER BY forum_order"); # caching needed
		$i = dbrows($parent_result);
		if($i > 0) {
			echo "<span class='side'>&nbsp;&nbsp;<strong>".$locale['412']."</strong>&nbsp;";
			while($parent_data = dbarray($parent_result)){
				$i--;
				if ($parent_data['forum_id'] != $data['forum_id']) {
				echo "<a href='".make_url(FORUM."viewforum.php?forum_id=".$parent_data['forum_id'], BASEDIR."forum-".$parent_data['forum_id']."-", $parent_data['forum_name'], ".html")."'>".$parent_data['forum_name']."</a>"; // Pimped: make_url
				if($i > 0) echo ", ";
				}
			}
			echo "</span>";
		} // Pimped Sub Cats <--
		echo "</td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$data['forum_threadcount']."</td>\n"; // Pimped Sub Cats
		echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".$data['forum_postcount']."</td>\n"; // Pimped Sub Cats
		echo "<td width='1%' class='tbl2' style='white-space:nowrap'>"; // Pimped Sub Cats -->
		$post = dbarray(dbquery("SELECT max(forum_lastpost) as lastpost FROM ".DB_FORUMS." WHERE forum_parent='".$data['forum_id']."'"));
		$condition = ($data['forum_lastpost'] > $post['lastpost']) ? $data['forum_lastpost'] : $post['lastpost'];
		$post_data = dbarray(dbquery("SELECT forum_lastpost, forum_lastuser, user_name, user_status FROM ".DB_FORUMS." LEFT JOIN ".DB_USERS." ON forum_lastuser=user_id WHERE forum_lastpost='".$condition."'"));		
		if ($post_data['forum_lastpost'] == 0) {
			echo $locale['405']."</td>\n</tr>\n";
		} else { // Pimped ->
				$thread_data = dbarray(dbquery("SELECT t.thread_id, t.thread_subject, t.thread_lastpostid FROM ".DB_THREADS." t
				left join ".DB_POSTS." p on p.post_id=t.thread_lastpostid
				WHERE t.thread_lastpost='".$post_data['forum_lastpost']."'"));
			echo "<a href='".make_url(FORUM."viewthread.php?thread_id=".$thread_data['thread_id'], BASEDIR."forum-thread-".$thread_data['thread_id']."-", $thread_data['thread_subject'], ".html")."#post_".$thread_data['thread_lastpostid']."' title='".$thread_data['thread_subject']."'>".trimlink($thread_data['thread_subject'], 35)."</a><br />"; // Pimped: make_url
			echo showdate("forumdate", $post_data['forum_lastpost'])."<br />\n";
			echo "<span class='small'>".$locale['406'].profile_link($post_data['forum_lastuser'], $post_data['user_name'], $post_data['user_status'])."</span></td>\n";
			echo "</tr>\n"; // Pimped Sub Cats <--
		}
	}
} else {
	echo "<tr>\n<td colspan='5' class='tbl1'>".$locale['407']."</td>\n</tr>\n";
}
echo "</table><!--sub_forum_idx_table-->\n<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
echo "<td class='forum'><br />\n";
echo "<img src='".get_image("foldernew")."' alt='".$locale['560']."' style='vertical-align:middle;' /> - ".$locale['409']."<br />\n";
echo "<img src='".get_image("folder")."' alt='".$locale['561']."' style='vertical-align:middle;' /> - ".$locale['410']."\n";
echo "</td><td align='right' valign='bottom' class='forum'>\n";
echo "<form name='searchform' method='get' action='".BASEDIR."search.php?stype=forums'>\n";
echo "<input type='hidden' name='stype' value='forums' />\n";
echo "<input type='text' name='stext' class='textbox' style='width:150px' />\n";
echo "<input type='submit' name='search' value='".$locale['550']."' class='button' />\n";
echo "</form>\n</td>\n</tr>\n</table><!--sub_forum_idx-->\n";
closetable();

// Pimped: Ads
if($settings['ads_in_show']) {
	opentable($settings['ads_in_name']);
	ob_start();
	eval("?>".stripslashes($settings['ads_in_code'])."<?php ");
	$contents = ob_get_contents();
	ob_end_clean();
	echo $contents;
	closetable();
}

// Pimped: Forum Statistics
if($settings['forum_statistics_forumstats'] || $settings['forum_statistics_topposters'] || $settings['forum_statistics_userstats']) {
	define("INCLUDE_FORUM_STATISTICS", true);
	require_once FORUM_INC."forum_statistics.php";
}

require_once TEMPLATES."footer.php";
?>