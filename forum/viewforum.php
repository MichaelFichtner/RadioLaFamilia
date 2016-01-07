<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/viewforum.php
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
if($settings['forum_ratings']) include_once INCLUDES_RATING."ratings_forum.php"; // Settings
include LOCALE.LOCALESET."forum/main.php";

$cache_subcats = array();

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }

if (!isset($_GET['forum_id']) || !isnum($_GET['forum_id'])) { redirect(make_url(FORUM."index.php", "forum", "", ".html")); } // Pimped: make_url

if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
$rowstart = $_GET['page'] > 0 ? ($_GET['page']-1) * $settings['threads_per_page'] : "0";

add_to_title($locale['global_200'].$locale['400']);

// Pimped: Sub-Cats -->
function subcats($forum_id) {
global $settings, $locale, $userdata, $lastvisited, $cache_subcats;

$access = ''; // Pimped: access
$aresult = dbquery("SELECT forum_access FROM ".DB_FORUMS." WHERE forum_parent='".(int)$_GET['forum_id']."' ORDER BY forum_order ASC");

while ($adata = dbarray($aresult)) {
	if ($access != '') { $access .= "|"; }
	$access .= $adata['forum_access'];
}
$a_result = dbquery("SELECT f.forum_id, f.forum_name, f.forum_description, f.forum_moderators, f.forum_lastpost, f.forum_image, f.forum_postcount, f.forum_threadcount, f.forum_lastuser, u.user_name, u.user_status FROM ".DB_FORUMS." f LEFT JOIN ".DB_USERS." u on f.forum_lastuser=u.user_id WHERE ".groupaccess('f.forum_access', $access)." AND forum_parent='".(int)$_GET['forum_id']."' ORDER BY forum_order");

if (dbrows($a_result) != 0 ) {
	
echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_idx_table'>\n<tr>\n";
echo "<td width='1%' class='tbl2' style='white-space:nowrap'>&nbsp;</td>\n";
echo "<td class='tbl2' ".($settings['forum_cat_icons'] == "1" ? "colspan='2' " : "").">".$locale['413']."</td>\n";
echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['402']."</td>\n";
echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['403']."</td>\n";
echo "<td width='1%' class='tbl2' style='white-space:nowrap'>".$locale['404']."</td>\n";
echo "</tr>\n";

while ($a_data = dbarray($a_result)) {
$cache_subcats[] = $a_data['forum_id']; // Pimped
echo "<tr>\n";
$moderators = '';
		if ($a_data['forum_moderators']) {
			$mod_groups = explode(".", $a_data['forum_moderators']);
			foreach ($mod_groups as $mod_group) {
				if ($moderators) $moderators .= ", ";
				$moderators .= $mod_group < nMEMBER ? group_link($mod_group, getgroupname($mod_group)) : getgroupname($mod_group); // Pimped: group_link()
			}
		}
		if ($a_data['forum_lastpost'] > $lastvisited) {
		$forum_match = "\|" . $a_data ['forum_lastpost'] . "\|" . $a_data ['forum_id'];
			if (iMEMBER && preg_match("({$forum_match}\.|{$forum_match}$)", $userdata['user_threads'])) {
				$fim = "<img src='".get_image("folder")."' alt='".$locale['561']."' />";
			} else {
				$fim = "<img src='".get_image("foldernew")."' alt='".$locale['560']."' />";
			}
		} else {
			$fim = "<img src='".get_image("folder")."' alt='".$locale['561']."' />";
		}

		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>$fim</td>\n";
		
		if($settings['forum_cat_icons'] == "1") {
			echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>";    
			if ($a_data['forum_image'] != '' && file_exists(IMAGES_FC.$a_data['forum_image'])) {
				echo "<img src='".IMAGES_FC.$a_data['forum_image']."' border='0' alt='' title='".$a_data['forum_name']."' />";     
			} else {
				echo "<img src='".IMAGES_FC."default.png' border='0' alt='' title='".$a_data['forum_name']."' />";
			}
			echo"</td>";
		}		
		
		echo "<td class='tbl1 forum_name'><!--forum_name--><a href='".make_url(FORUM."viewforum.php?forum_id=".$a_data['forum_id'], BASEDIR."forum-".$a_data['forum_id']."-", $a_data['forum_name'], ".html")."'>".$a_data['forum_name']."</a><br />\n"; // Pimped: make_url
		if ($a_data['forum_description'] || $moderators) {
			echo "<span class='small'>".$a_data['forum_description'].($a_data['forum_description'] && $moderators ? "<br />\n" : "");
			echo ($moderators ? "<strong>".$locale['411']."</strong>".$moderators."</span>\n" : "</span>\n")."<br />\n";
		}
		whoishere_show("index", $a_data['forum_id']); // Pimped: Who is here
		echo "</td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$a_data['forum_threadcount']."</td>\n";
		echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".$a_data['forum_postcount']."</td>\n";
		echo "<td width='1%' class='tbl2' style='white-space:nowrap'>";
		
		
		if ($a_data['forum_lastpost'] == 0) {
			echo $locale['405']."</td>\n</tr>\n";
		} else { // Pimped ->
				$thread_data = dbarray(dbquery("SELECT t.thread_id, t.thread_subject, t.thread_lastpostid FROM ".DB_THREADS." t
				left join ".DB_POSTS." p on p.post_id=t.thread_lastpostid
				WHERE t.thread_lastpost='".$a_data['forum_lastpost']."'"));
			echo "<a href='".make_url(FORUM."viewthread.php?thread_id=".$thread_data['thread_id'], BASEDIR."forum-thread-".$thread_data['thread_id']."-", $thread_data['thread_subject'], ".html")."#post_".$thread_data['thread_lastpostid']."' title='".$thread_data['thread_subject']."'>".trimlink($thread_data['thread_subject'], 35)."</a><br />"; // Pimped: make_url
			echo showdate("forumdate", $a_data['forum_lastpost'])."<br />\n";
			echo "<span class='small'>".$locale['406'].profile_link($a_data['forum_lastuser'], $a_data['user_name'], $a_data['user_status'])."</span></td>\n";
			echo "</tr>\n"; // Pimped Sub Cats <--
		}
		

		}
echo "</table>";
echo "<div style='margin:5px'></div>";
	}
} // Pimped <--
$result = dbquery(
	"SELECT f.forum_id, f.forum_cat, f.forum_parent, f.forum_name, f.forum_moderators, f.forum_access, f.forum_post, f.forum_markresolved,
	f2.forum_name AS forum_cat_name
	FROM ".DB_FORUMS." f
	LEFT JOIN ".DB_FORUMS." f2 ON f.forum_cat=f2.forum_id
	WHERE f.forum_id='".$_GET['forum_id']."'"
);
if (dbrows($result)) {
	$fdata = dbarray($result);
	if((!checkgroup($fdata['forum_access']) && !checkgroup($fdata['forum_moderators'])) || !$fdata['forum_cat']){
		redirect(make_url(FORUM."index.php", "forum", "", ".html"));
	} // Pimped: make_url
} else {
	redirect(make_url(FORUM."index.php", "forum", "", ".html")); // Pimped: make_url
}

if ($fdata['forum_post']) {
	$can_post = checkgroup($fdata['forum_post']);
} else {
	$can_post = false;
}

// Pimped: Forum Observer
require_once FORUM_INC."forum_observer.php";
whoishere_observer("forum");

// locale dependent forum buttons
if (is_array($fusion_images)) {
	if ($settings['locale'] != "English") {
		$newpath = "";
		$oldpath = explode("/", $fusion_images['newthread']);
		for ($i = 0; $i < count($oldpath) - 1; $i++) {
			$newpath .= $oldpath[$i]."/";
		}
		if (is_dir($newpath.$settings['locale'])) {
			redirect_img_dir($newpath, $newpath.$settings['locale']."/");
		}
	}
}
// locale dependent forum buttons

if (iMODERATOR) { define("iMOD", true); }

if (!defined("iMOD") && iMEMBER && $fdata['forum_moderators']) {
	$mod_groups = explode(".", $fdata['forum_moderators']);
	foreach ($mod_groups as $mod_group) {
		if (!defined("iMOD") && checkgroup($mod_group)) { define("iMOD", true); }
	}
}

if (!defined("iMOD")) { define("iMOD", false); }

if ($fdata['forum_parent'] != 0 ) { // Pimped: Sub-Cats ->
	$sub_data = dbarray(dbquery("SELECT forum_id, forum_name FROM ".DB_FORUMS." WHERE forum_id='".$fdata['forum_parent']."'"));
	$caption = $fdata['forum_cat_name']." &raquo; <a href='".make_url(FORUM."viewforum.php?forum_id=".$sub_data['forum_id'], BASEDIR."forum-".$sub_data['forum_id']."-", $sub_data['forum_name'], ".html")."'>".$sub_data['forum_name']."</a> &raquo; ".$fdata['forum_name'];
} else {
	$caption = $fdata['forum_cat_name']." &raquo; ".$fdata['forum_name'];
} // Pimped <-

add_to_title($locale['global_201'].$fdata['forum_name']);

if (isset($_POST['delete_threads']) && iMOD) {
	$thread_ids = "";
	if (isset($_POST['check_mark']) && is_array($_POST['check_mark'])) {
		foreach ($_POST['check_mark'] as $thisnum) {
			if (isnum($thisnum)) { $thread_ids .= ($thread_ids ? "," : "").$thisnum;
						$log_result = dbquery("SELECT forum_id,thread_subject FROM ".DB_THREADS." WHERE thread_id ='".$thisnum."'"); // pimped
						$log_data = dbarray($log_result);  // pimped
						log_admin_action("forum", "delete_2", $log_data['forum_id'], $thisnum, $log_data['thread_subject']); // Pimped
			}
		}
	}
	if ($thread_ids) {
		$result = dbquery("SELECT post_author, COUNT(post_id) as num_posts FROM ".DB_POSTS." WHERE thread_id IN (".$thread_ids.") GROUP BY post_author");
		if (dbrows($result)) {
			while ($pdata = dbarray($result)) {
				$result2 = dbquery("UPDATE ".DB_USERS." SET user_posts=user_posts-".$pdata['num_posts']." WHERE user_id='".$pdata['post_author']."'");
			}
		}
		$result = dbquery("SELECT attach_name FROM ".DB_FORUM_ATTACHMENTS." WHERE thread_id IN (".$thread_ids.")");
		if(dbrows($result)) {
			while ($data = dbarray($result)) {
				if(file_exists(FORUM_ATT.$data['attach_name'])) {
					unlink(FORUM_ATT.$data['attach_name']);
				}
			}
		}
		$result = dbquery("DELETE FROM ".DB_POSTS." WHERE thread_id IN (".$thread_ids.") AND forum_id='".$_GET['forum_id']."'");
		$deleted_posts = mysql_affected_rows();
		$result = dbquery("DELETE FROM ".DB_THREADS." WHERE thread_id IN (".$thread_ids.") AND forum_id='".$_GET['forum_id']."'");
		$deleted_threads = mysql_affected_rows();
		$result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE thread_id IN (".$thread_ids.")");
		$result = dbquery("DELETE FROM ".DB_FORUM_ATTACHMENTS." WHERE thread_id IN (".$thread_ids.")");
		$result = dbquery("DELETE FROM ".DB_FORUM_POLL_OPTIONS." WHERE thread_id IN (".$thread_ids.")");
		$result = dbquery("DELETE FROM ".DB_FORUM_POLL_VOTERS." WHERE thread_id IN (".$thread_ids.")");
		$result = dbquery("DELETE FROM ".DB_FORUM_POLLS." WHERE thread_id IN (".$thread_ids.")");
		if ($settings['enable_tags']) { // Pimped: tag
			require_once INCLUDES."tag_include.php";
			delete_tags($thread_ids, "F"); 
		}
		$result = dbquery("SELECT post_datestamp, post_author FROM ".DB_POSTS."
		WHERE forum_id='".$_GET['forum_id']."' ORDER BY post_datestamp DESC LIMIT 1");
		if (dbrows($result)) {
			$ldata = dbarray($result);
			$forum_lastpost = "forum_lastpost='".$ldata['post_datestamp']."', forum_lastuser='".$ldata['post_author']."'";
		} else {
			$forum_lastpost = "forum_lastpost='0', forum_lastuser='0'";
		}
		$result = dbquery("UPDATE ".DB_FORUMS." SET ".$forum_lastpost.", forum_postcount=forum_postcount-".$deleted_posts.", forum_threadcount=forum_threadcount-".$deleted_threads." WHERE forum_id='".$_GET['forum_id']."'");
	}
	$rows_left = dbcount("(thread_id)", DB_THREADS, "forum_id='".$_GET['forum_id']."'") - 3;
	if ($rows_left <= $rowstart && $rowstart > 0) {
		$rowstart = ((ceil($rows_left / $settings['threads_per_page'])-1) * $settings['threads_per_page']);
	}
	redirect(($rowstart == 0 ? make_url(FORUM.FUSION_SELF."?forum_id=".$_GET['forum_id'], BASEDIR."forum-".$_GET['forum_id']."-", $fdata['forum_name'], ".html") : make_url(FORUM.FUSION_SELF."?forum_id=".$_GET['forum_id']."&amp;page=".$_GET['page'], BASEDIR."forum-".$_GET['forum_id']."-page-".$_GET['page']."-", $fdata['forum_name'], ".html"))); // Pimped: make_url and if(rowstart = 0)..
}

opentable($locale['450'].": ".$fdata['forum_name']);
echo "<!--pre_forum--><div class='tbl2 forum_breadcrumbs'><a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$settings['sitename']."</a> &raquo; ".$caption."</div>\n"; // Pimped style and make_url

$rows = dbcount("(thread_id)", DB_THREADS, "forum_id='".(int)$_GET['forum_id']."' AND thread_hidden='0'");

$post_info = "";

if ($rows > $settings['threads_per_page'] || (iMEMBER && $can_post)) {
	echo "<table cellspacing='0' cellpadding='0' width='100%'>\n<tr>\n";
	whoishere_show("forum", $_GET['forum_id'], ((iMEMBER && $can_post && !($rows > $settings['threads_per_page'])) ? true : false )); // Pimped: Who is here
	if ($rows > $settings['threads_per_page']) {
		$post_info .= "<td style='padding:4px 0px 4px 0px'>";
		$post_info .= pagination(true,(int)$rowstart,$settings['threads_per_page'],$rows,3,FUSION_SELF."?forum_id=".$_GET['forum_id']."&amp;", BASEDIR."forum","-",$_GET['forum_id'],"-page-", "-", $fdata['forum_name']);
		$post_info .= "</td>\n";
	} // Pimped: url rewrite
	if (iMEMBER && $can_post) {
		$post_info .= "<td align='right' style='padding:4px 0px 4px 0px'>";
		$post_info .= "<a href='".make_url(FORUM."post.php?action=newthread&forum_id=".(int)$_GET['forum_id'], BASEDIR."forum-newthread-".(int)$_GET['forum_id'], "", ".html")."'>";
		$post_info .= "<img src='".get_image("newthread")."' alt='".$locale['566']."' style='border:0px;' /></a></td>\n"; // Pimped: make_url
	}
	echo $post_info;
	echo "</tr>\n</table>\n";
} else {
	whoishere_show("forum", $_GET['forum_id'], false); // Pimped: Who is here

}

subcats($_GET['forum_id']); // Pimped: Sub-Cats
if (iMOD) { echo "<form name='mod_form' method='post' action='".($rowstart == 0 ? make_url(FORUM.FUSION_SELF."?forum_id=".$_GET['forum_id'], BASEDIR."forum-".$_GET['forum_id']."-", $fdata['forum_name'], ".html") : make_url(FORUM.FUSION_SELF."?forum_id=".$_GET['forum_id']."&amp;page=".$_GET['page'], BASEDIR."forum-".$_GET['forum_id']."-page-".$_GET['page']."-", $fdata['forum_name'], ".html"))."'>\n"; } // Pimped: make_url and if(rowstart = 0)..
echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_table'>\n<tr>\n";
echo "<td width='1%' class='tbl2' style='white-space:nowrap'>&nbsp;</td>\n";
echo "<td class='tbl2'>".$locale['451']."</td>\n";
if($settings['forum_ratings']) echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>Ratings"."</td>\n"; // Ratings
echo "<td width='1%' class='tbl2' style='white-space:nowrap'>".$locale['452']."</td>\n";
echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['453']."</td>\n";
echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['454']."</td>\n";
echo "<td width='1%' class='tbl2' style='white-space:nowrap'>".$locale['404']."</td>\n</tr>\n";

if ($rows) {
	$result = dbquery(
		"SELECT t.thread_id, t.thread_subject, ".($settings['forum_thread_description'] ? "t.thread_description, " : "")."t.thread_author, 
		t.thread_views, t.thread_lastpost, t.thread_lastpostid, t.thread_lastuser, t.thread_postcount, t.thread_locked, t.thread_sticky, t.thread_resolved,
		tu1.user_name AS user_author, tu1.user_status AS status_author, tu2.user_name AS user_lastuser, tu2.user_status AS status_lastuser
		FROM ".DB_THREADS." t
		LEFT JOIN ".DB_USERS." tu1 ON t.thread_author = tu1.user_id
		LEFT JOIN ".DB_USERS." tu2 ON t.thread_lastuser = tu2.user_id
		WHERE t.forum_id='".$_GET['forum_id']."' AND thread_hidden='0'
		ORDER BY thread_sticky DESC, thread_lastpost DESC LIMIT ".(int)$rowstart.",".(int)$settings['threads_per_page']
		);
	$numrows = dbrows($result);
	while ($tdata = dbarray($result)) {
		$thread_match = $tdata['thread_id']."\|".$tdata['thread_lastpost']."\|".$fdata['forum_id'];
		echo "<tr>\n";
		if ($tdata['thread_locked']) {
			echo "<td align='center' width='25' class='tbl2'><img src='".get_image("folderlock")."' alt='".$locale['564']."' /></td>";
		} else  {
			if ($tdata['thread_lastpost'] > $lastvisited) {
				if (iMEMBER && preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
					$folder = "<img src='".get_image("folder")."' alt='".$locale['561']."' />";
				} else {
					$folder = "<img src='".get_image("foldernew")."' alt='".$locale['560']."' />";
				}
			} else {
				$folder = "<img src='".get_image("folder")."' alt='".$locale['561']."' />";
			}
			echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>$folder</td>";
		}
		$reps = ceil($tdata['thread_postcount'] / $settings['posts_per_page']);
		$threadsubject = "<a href='".make_url(FORUM."viewthread.php?thread_id=".$tdata['thread_id'], BASEDIR."forum-thread-".$tdata['thread_id']."-", $tdata['thread_subject'], ".html")."'>".$tdata['thread_subject']."</a>"; // Pimped: make_url
		$threadsubject .= ($fdata['forum_markresolved'] == "1" && $tdata['thread_resolved'] == "1") ? " ".$locale['458']." " : "";
		if($settings['forum_thread_description'] && $tdata['thread_description'] != "") { 
				$threadsubject .= "<br /><span class='small2'>".$tdata['thread_description']."</span>";
		}
		if ($reps > 1) {
			$ctr2 = 1; $pages = ""; $points = false; // Pimped
			while ($ctr2 <= $reps) {
				if($reps <= 5 || $ctr2 == 1 || $ctr2 > $reps-5) { // Pimped
					$pnum = "<a href='".make_url(FORUM."viewthread.php?thread_id=".$tdata['thread_id']."&amp;page=".$ctr2, BASEDIR."forum-thread-".$tdata['thread_id']."-start".$ctr2."-", $tdata['thread_subject'], ".html")."'>".$ctr2."</a> "; // Pimped: make_url
				} else {
					if(!$points) { // Pimped
						$pnum = "... ";
						$points = true;
					} else {
						$pnum = "";
					}
				}
				$pages = $pages.$pnum; $ctr2++;
			}
			$threadsubject .= "<br />(".$locale['455'].trim($pages).")";
		}
		echo "<td width='100%' class='tbl1'>";
		if (iMOD) { echo "<input type='checkbox' name='check_mark[]' value='".$tdata['thread_id']."' />\n"; }
		if ($tdata['thread_sticky'] == 1) {
			echo "<img src='".get_image("stickythread")."' alt='".$locale['474']."' style='vertical-align:middle;' />\n";
		}
		if($settings['forum_ratings']) {
			echo $threadsubject."</td><td width='1%' class='tbl1' style='white-space:nowrap'>".ratings_forum($tdata['thread_id'])."</td>\n";
			#echo $threadsubject."<div style='text-align: right;'>".ratings_forum($tdata['thread_id'])."</div></td>\n";
		} else {
			echo $threadsubject."</td>\n";
		}
		echo "<td width='1%' class='tbl2' style='white-space:nowrap'>".profile_link($tdata['thread_author'], $tdata['user_author'], $tdata['status_author'])."</td>\n";
		echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".$tdata['thread_views']."</td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".($tdata['thread_postcount']-1)."</td>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".showdate("forumdate", $tdata['thread_lastpost'])."<br />\n";
		
		$gotolastpost = "&nbsp;<a href='".make_url(FORUM."viewthread.php?thread_id=".$tdata['thread_id']."&amp;pid=".$tdata['thread_lastpostid'], BASEDIR."forum-thread-".$tdata['thread_id']."-pid".$tdata['thread_lastpostid']."-", $tdata['thread_subject'], ".html")."#post_".$tdata['thread_lastpostid']."' title='".$tdata['thread_subject']."'><img src='".IMAGES."forum/gotopost.gif' border='0' alt='' /></a>"; // Go To Last Post
		
		echo "<span class='small'>".$locale['406'].profile_link($tdata['thread_lastuser'], $tdata['user_lastuser'], $tdata['status_lastuser']).$gotolastpost."</span></td>\n";
		echo "</tr>\n";
	}
	echo "</table><!--sub_forum_table-->\n";
} else {
	if (!$rows) {
		echo "<tr>\n<td colspan='6' class='tbl1' style='text-align:center'>".$locale['456']."</td>\n</tr>\n</table><!--sub_forum_table-->\n";
	} else {
		echo "</table><!--sub_forum_table-->\n";
	}
}

if (iMOD) {
	if ($rows) {
		echo "<table cellspacing='0' cellpadding='0' width='100%'>\n<tr>\n<td style='padding-top:5px'>";
		echo "<a href='#' onclick=\"javascript:setChecked('mod_form','check_mark[]',1);return false;\">".$locale['460']."</a> ::\n";
		echo "<a href='#' onclick=\"javascript:setChecked('mod_form','check_mark[]',0);return false;\">".$locale['461']."</a></td>\n";
		echo "<td align='right' style='padding-top:5px'><input type='submit' name='delete_threads' value='".$locale['462']."' class='button' onclick=\"return confirm('".$locale['463']."');\" /></td>\n";
		echo "</tr>\n</table>\n";
	}
	echo "</form>\n";
	if ($rows) {
		echo "<script type='text/javascript'>\n"."function setChecked(frmName,chkName,val) {\n";
		echo "dml=document.forms[frmName];\n"."len=dml.elements.length;\n"."for(i=0;i < len;i++) {\n";
		echo "if(dml.elements[i].name == chkName) {\n"."dml.elements[i].checked = val;\n}\n}\n}\n";
		echo "</script>\n";
	}
}

if($post_info != "") {
	echo "<table cellspacing='0' cellpadding='0' width='100%'>\n<tr>\n";
	echo $post_info;
	echo "</tr>\n</table>\n";
}

$forum_list = ''; $current_cat = '';
$testaccess = ''; // Pimped: access
$request = dbquery("SELECT forum_access FROM ".DB_FORUMS." WHERE forum_cat!='0' ORDER BY forum_order ASC");
while ($datarequest = dbarray($request)) {
if ($testaccess != '') { $testaccess .= "|"; }
$testaccess .= $datarequest['forum_access'];
}
function jump_to_forum($forum_id){
	global $fdata, $testaccess;
	$jump_list = "";$sel = "";
	$result = dbquery("SELECT f.forum_id, f.forum_parent, f2.forum_name AS forum_cat_name FROM ".DB_FORUMS." f
	LEFT JOIN ".DB_FORUMS." f2 ON f2.forum_id=f.forum_id
	WHERE ".groupaccess('f.forum_access', $testaccess)." AND f2.forum_parent='$forum_id'");
	while($data = dbarray($result)){
	if(URL_REWRITE) { $value = $data['forum_id']."-".clean_subject_urlrewrite($data['forum_cat_name']); } else { $value = $data['forum_id']; } // Pimped for make_url (for javascript)
	$sel = ($data['forum_id'] == $fdata['forum_id'] ? " selected='selected'" : "");  // Pimped: --> Forum-Cats and make_url (for javascript)
		$jump_list .= "<option value='".$value."'$sel>&nbsp;&nbsp;-".$data['forum_cat_name']."</option>\n";
	}
	return $jump_list;
}
$result = dbquery( // Pimped: Sub-Cats
	"SELECT f.forum_id, f.forum_name, f.forum_cat, f.forum_parent, f2.forum_name AS forum_cat_name
	FROM ".DB_FORUMS." f
	INNER JOIN ".DB_FORUMS." f2 ON f.forum_cat=f2.forum_id
	WHERE ".groupaccess('f.forum_access', $testaccess)." AND f.forum_cat!='0' AND f.forum_parent='0' ORDER BY f2.forum_order ASC, f.forum_order ASC"
);
while ($data2 = dbarray($result)) {
	if ($data2['forum_cat_name'] != $current_cat) {
		if ($current_cat != "") { $forum_list .= "</optgroup>\n"; }
		$current_cat = $data2['forum_cat_name'];
		$forum_list .= "<optgroup label='".$data2['forum_cat_name']."'>\n";
	}
	$sel = ($data2['forum_id'] == $fdata['forum_id'] ? " selected='selected'" : ""); // Pimped: --> Forum-Cats and make_url (for javascript)
	if(URL_REWRITE) { $value = $data2['forum_id']."-".clean_subject_urlrewrite($data2['forum_name']); } else { $value = $data2['forum_id']; } // Pimped for make_url (for javascript)
	// Pimped: Sub-Cats ->
	$forum_list .= "<option value='".$value."'$sel>".$data2['forum_name']."</option>\n";
	$forum_list .= jump_to_forum($data2['forum_id']);
	 // Pimped: Forum-Cats and make_url (for javascript) <--
}
$forum_list .= "</optgroup>\n";
echo "<div style='padding-top:5px'>\n".$locale['540']."<br />\n";
echo "<select name='jump_id' class='textbox' onchange=\"jumpforum(this.options[this.selectedIndex].value);\">";
echo $forum_list."</select>\n</div>\n";

echo "<div><hr />\n";
echo "<img src='".get_image("foldernew")."' alt='".$locale['560']."' style='vertical-align:middle;' /> - ".$locale['470']."<br />\n";
echo "<img src='".get_image("folder")."' alt='".$locale['561']."' style='vertical-align:middle;' /> - ".$locale['472']."<br />\n";
echo "<img src='".get_image("folderlock")."' alt='".$locale['564']."' style='vertical-align:middle;' /> - ".$locale['473']."<br />\n";
echo "<img src='".get_image("stickythread")."' alt='".$locale['563']."' style='vertical-align:middle;' /> - ".$locale['474']."\n";
echo "</div><!--sub_forum-->\n";
closetable();

echo "<script type='text/javascript'>\n"."function jumpforum(forumid) {\n";
echo "document.location.href='".make_url(FORUM."viewforum.php?forum_id='+forumid", BASEDIR."forum-'+forumid+'", "", ".html'").";\n}\n"; // Pimped: make_url (for javascript)
echo "</script>\n";

$mysql_subcats = ''; // Pimped
if (is_array($cache_subcats) && count($cache_subcats)) {
	foreach ($cache_subcats as $subcat) {
		$mysql_subcats .= " OR forum_id='".(int)$subcat."'";
	}
}

list($threadcount, $postcount) = dbarraynum(dbquery("SELECT COUNT(thread_id), SUM(thread_postcount)
FROM ".DB_THREADS." WHERE thread_hidden='0' AND forum_id='".(int)$_GET['forum_id']."'".$mysql_subcats )); // Pimped
if(isnum($threadcount) && isnum($postcount)){
	dbquery("UPDATE ".DB_FORUMS." SET forum_postcount='".$postcount."', forum_threadcount='".$threadcount."' WHERE forum_id='".(int)$_GET['forum_id']."'"); // Pimped
}

require_once TEMPLATES."footer.php";
?>