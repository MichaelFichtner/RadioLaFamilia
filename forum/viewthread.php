<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/viewthread.php
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
require_once INCLUDES."forum_include.php";
require_once TEMPLATES."header.php";
include LOCALE.LOCALESET."forum/main.php";

define("IS_FORUM", true);

if($settings['ads_vf_show']) {
	if (file_exists(LOCALE.LOCALESET."ads_system.php")) {
			include_once LOCALE.LOCALESET."ads_system.php";
		} else {
			include_once LOCALE."English/ads_system.php";
	}
}

if($settings['warning_system']) include_once INCLUDES."warning.inc.php"; // Pimped

if($settings['forum_post_ratings']) { // Pimped
require_once FORUM_INC."forum_post_rating.php";
add_to_head("<script type='text/javascript'><!--
function show(id) {
var d = document.getElementById(id);
if (d.style.display=='none') { d.style.display='block'; } else { d.style.display='none'; }
}
//--></script>
<script src='".FORUM_INC."forum_post_rating_boxover.js' type='text/javascript'></script>");
}

$posts_per_page = $settings['posts_per_page'];

add_to_title($locale['global_200'].$locale['400']);

if (!isset($_GET['thread_id']) || !isnum($_GET['thread_id'])) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url

if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 0; }
$_GET['rowstart'] = $_GET['page'] > 0 ? ($_GET['page']-1) * $posts_per_page : "0";

$result = dbquery(
	"SELECT t.thread_id, t.thread_subject, t.thread_lastpost, t.thread_poll, t.thread_sticky, t.thread_locked, t.thread_resolved, t.thread_author,
	f.forum_id, f.forum_cat, f.forum_parent, f.forum_name, f.forum_moderators, f.forum_access,
	f.forum_post, f.forum_reply, f.forum_vote, f.forum_attach, f.forum_markresolved,
	f2.forum_name AS forum_cat_name
	FROM ".DB_THREADS." t
	LEFT JOIN ".DB_FORUMS." f ON t.forum_id=f.forum_id
	LEFT JOIN ".DB_FORUMS." f2 ON f.forum_cat=f2.forum_id
	WHERE t.thread_id='".(int)$_GET['thread_id']."' AND t.thread_hidden='0' LIMIT 1
"); // Pimped #   
if (dbrows($result)) {
	$fdata = dbarray($result);
	if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url
} else {
	redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
}

if ($fdata['forum_post'] != 0 && checkgroup($fdata['forum_post'])) {
	$can_post = true;
} else {
	$can_post = false;
}

if ($fdata['forum_reply'] != 0 && checkgroup($fdata['forum_reply'])) {
	$can_reply = true;
} else {
	$can_reply = false;
}

if ($settings['forum_edit_lock'] == 1) {
	$lock_edit = true;
} else {
	$lock_edit = false;
}

// Pimped: Forum Observer
require_once FORUM_INC."forum_observer.php";
whoishere_observer("thread");

//locale dependent forum buttons
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
//locale dependent forum buttons

$mod_groups = explode(".", $fdata['forum_moderators']);

if (iMODERATOR) { define("iMOD", true); }

if (!defined("iMOD") && iMEMBER && $fdata['forum_moderators']) {
	foreach ($mod_groups as $mod_group) {
		if (!defined("iMOD") && checkgroup($mod_group)) { define("iMOD", true); }
	}
}

if (!defined("iMOD")) { define("iMOD", false); }

if (iMOD && (((isset($_POST['delete_posts']) || isset($_POST['move_posts'])) && isset($_POST['delete_post'])) || isset($_GET['error']))) { 
	require_once FORUM."viewthread_options.php"; 
}

$user_field = array("user_sig" => false, "user_trank" => false, "user_web" => false,
"user_location" => false, "user_birthdate" => false, "user_genderimage" => false, "user_gendertext" => false);

if (iMEMBER) {
	$thread_match = $fdata['thread_id']."\|".$fdata['thread_lastpost']."\|".$fdata['forum_id'];
	if (($fdata['thread_lastpost'] > $lastvisited) && !preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads'])) {
		$result = dbquery("UPDATE ".DB_USERS." SET user_threads='".$userdata['user_threads'].".".stripslashes($thread_match)."'
		WHERE user_id='".$userdata['user_id']."'");
	}
	
	if (isset($userdata['user_sig'])) { $user_field['user_sig'] = true; }
	if (isset($userdata['user_trank'])) { $user_field['user_trank'] = true; }
	if (isset($userdata['user_web'])) { $user_field['user_web'] = true; }
	if (isset($userdata['user_location'])) { $user_field['user_location'] = true; }
	if (isset($userdata['user_birthdate'])) { $user_field['user_birthdate'] = true; }
	if (isset($userdata['user_genderimage'])) { $user_field['user_genderimage'] = true; }
	if (isset($userdata['user_gendertext'])) { $user_field['user_gendertext'] = true; }

	if (isset($_POST['cast_vote']) && (isset($_POST['poll_option']) && isnum($_POST['poll_option']))) {
		$result = dbquery("SELECT forum_vote_user_id FROM ".DB_FORUM_POLL_VOTERS."
		WHERE forum_vote_user_id='".$userdata['user_id']."' AND thread_id='".$_GET['thread_id']."'");
		if (!dbrows($result)) {
			$result = dbquery("UPDATE ".DB_FORUM_POLL_OPTIONS." SET forum_poll_option_votes=forum_poll_option_votes+1
			WHERE thread_id='".$_GET['thread_id']."' AND forum_poll_option_id='".$_POST['poll_option']."'");
			$result = dbquery("UPDATE ".DB_FORUM_POLLS." SET forum_poll_votes=forum_poll_votes+1 WHERE thread_id='".$_GET['thread_id']."'");
			$result = dbquery("INSERT INTO ".DB_FORUM_POLL_VOTERS." (thread_id, forum_vote_user_id, forum_vote_user_ip)
			VALUES ('".$_GET['thread_id']."', '".$userdata['user_id']."', '".USER_IP."')");
		}
		redirect(make_url(FORUM.FUSION_SELF."?thread_id=".(int)$_GET['thread_id'],
		BASEDIR."forum-thread-".(int)$_GET['thread_id']."-", $fdata['thread_subject'], ".html")); // Pimped: make_url
	}

} else {

	$result = dbquery("SELECT field_name FROM ".DB_USER_FIELDS."
	WHERE field_name='user_sig' OR field_name='user_trank' OR field_name='user_web'
	OR field_name='user_location' OR field_name='user_birthdate' OR field_name='user_genderimage' OR field_name='user_gendertext'");
	while ($data = dbarray($result)) {
		$user_field[$data['field_name']] = true;
	}
}

if (isset($_GET['pid']) && isnum($_GET['pid'])) {
	$reply_count = dbcount("(post_id)", DB_POSTS, "thread_id='".$fdata['thread_id']."' AND post_id<='".$_GET['pid']."' AND post_hidden='0'");
	if ($reply_count > $posts_per_page) { $_GET['rowstart'] = ((ceil($reply_count / $posts_per_page)-1) * $posts_per_page); }
}

if ($fdata['forum_parent'] != 0 ) { // Pimped: Sub-Cats & make_url -->
$sub_data = dbarray(dbquery("SELECT forum_id, forum_name FROM ".DB_FORUMS." WHERE forum_id='".$fdata['forum_parent']."'"));
$caption = $fdata['forum_cat_name']." &raquo; <a href='".make_url(FORUM."viewforum.php?forum_id=".$sub_data['forum_id'], BASEDIR."forum-".$sub_data['forum_id']."-", $sub_data['forum_name'], ".html")."'>".$sub_data['forum_name']."</a> &raquo; <a href='".make_url(FORUM."viewforum.php?forum_id=".$fdata['forum_id'], BASEDIR."forum-".$fdata['forum_id']."-", $fdata['forum_name'], ".html")."'>".$fdata['forum_name']."</a>";
} else {
$caption = $fdata['forum_cat_name']." &raquo; <a href='".make_url(FORUM."viewforum.php?forum_id=".$fdata['forum_id'], BASEDIR."forum-".$fdata['forum_id']."-", $fdata['forum_name'], ".html")."'>".$fdata['forum_name']."</a>";
} // Pimped: Sub-Cats & make_url <--


list($rows, $last_post) = dbarraynum(dbquery("SELECT COUNT(post_id), MAX(post_id) 
FROM ".DB_POSTS." WHERE thread_id='".(int)$_GET['thread_id']."' AND post_hidden='0' GROUP BY thread_id"));

// Next / Prev Thread
$prev = ""; $next = "";
if($settings['threads_show_next_prev']) {
	$ex_prev = dbcount("(thread_id)", DB_THREADS, 
		"forum_id='".(int)$fdata['forum_id']."' AND thread_lastpost < '".(int)$fdata['thread_lastpost']."' ORDER BY thread_lastpost DESC LIMIT 1");
	$prev_thread['thread_id'] = 0;
	if ($ex_prev > 0) {
		$prev_thread = dbarray(dbquery("SELECT thread_id, thread_subject
		FROM ".DB_THREADS."
		WHERE forum_id='".(int)$fdata['forum_id']."' AND thread_lastpost<'".(int)$fdata['thread_lastpost']."' ORDER BY thread_lastpost DESC LIMIT 1"));
	}
	$ex_next = dbcount("(thread_id)", DB_THREADS, 
		"forum_id='".(int)$fdata['forum_id']."' AND thread_lastpost > '".(int)$fdata['thread_lastpost']."' ORDER BY thread_lastpost ASC LIMIT 1");
	$next_thread['thread_id'] = 0;
	if ($ex_next > 0) {
		$next_thread = dbarray(dbquery("SELECT thread_id, thread_subject
		FROM ".DB_THREADS."
		WHERE forum_id='".(int)$fdata['forum_id']."' AND thread_lastpost>'".(int)$fdata['thread_lastpost']."' ORDER BY thread_lastpost ASC LIMIT 1"));
	}
	if ($next_thread['thread_id'] > 0) {
		$a = "<a href='".make_url(FORUM."viewthread.php?thread_id=".$next_thread['thread_id'],
		BASEDIR.SEO_F_THREAD_A.SEO_F_THREAD_B1.$next_thread['thread_id'].SEO_F_THREAD_B2,
		$next_thread['thread_subject'], SEO_F_THREAD_C)."' title='".$next_thread['thread_subject']."'>";
		$b = "</a>";
		$next .= $a.$next_thread['thread_subject'].$b." ";
		$next .= $a."<img src='".THEME."images/right.gif' title='' alt='' />".$b;
	}
	if ($prev_thread['thread_id'] > 0) {
		$a = "<a href='".make_url(FORUM."viewthread.php?thread_id=".$prev_thread['thread_id'],
		BASEDIR.SEO_F_THREAD_A.SEO_F_THREAD_B1.$prev_thread['thread_id'].SEO_F_THREAD_B2,
		$prev_thread['thread_subject'], SEO_F_THREAD_C)."' title='".$prev_thread['thread_subject']."'>";
		$b = "</a>";
		$prev .= $a."<img src='".THEME."images/left.gif' title='' alt='' />".$b." ";
		$prev .= $a.$prev_thread['thread_subject'].$b;
	}
}

opentable($locale['500'].": ".$fdata['thread_subject']);
echo "<!--pre_forum_thread--><div class='tbl2 forum_breadcrumbs' style='margin:0px 0px 4px 0px'><a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$settings['sitename']."</a> &raquo; ".$caption."</div>\n"; // Pimped: make_url

if (($rows > $posts_per_page) || ($can_post || $can_reply)) {
	echo "<table cellspacing='0' cellpadding='0' width='100%'>\n<tr>\n";
	whoishere_show("thread", $_GET['thread_id'], true); // Pimped: Who is here
	if ($rows > $posts_per_page) { echo "<td style='padding:4px 0px 4px 0px'>".pagination(true,$_GET['rowstart'],$posts_per_page,$rows,3,FUSION_SELF."?thread_id=".$_GET['thread_id']."&amp;", BASEDIR."forum-thread","-",$_GET['thread_id'],"-start","-",$fdata['thread_subject'])."</td>\n"; } // Pimped: make_url xy
	if (iMEMBER && $can_post) {
		echo "<td align='right' style='padding:0px 0px 4px 0px'>\n<!--pre_forum_buttons-->\n";
		if (!$fdata['thread_locked'] && $can_reply) {
			echo "<a href='".make_url(FORUM."post.php?action=reply&amp;forum_id=".$fdata['forum_id']."&amp;thread_id=".$_GET['thread_id'], FORUM."post.php?action=reply&amp;forum_id=".$fdata['forum_id']."&amp;thread_id=".$_GET['thread_id'], "", "")."'><img src='".get_image("reply")."' alt='".$locale['565']."' style='border:0px' /></a>\n"; // Pimped: make_url, but no seo url-rewrite
		}
		if ($can_post) {
			echo "<a href='".make_url(FORUM."post.php?action=newthread&amp;forum_id=".(int)$fdata['forum_id'], BASEDIR."forum-newthread-".(int)$fdata['forum_id'], "", ".html")."'><img src='".get_image("newthread")."' alt='".$locale['566']."' style='border:0px' /></a>\n</td>\n"; // Pimped: make_url
		}
	}
	echo "</tr>\n</table>\n";
} else {
	whoishere_show("thread", $_GET['thread_id'], false); // Pimped: Who is here
}

// Thread Ratings
if($settings['forum_ratings']) {
	require_once INCLUDES_RATING."ratings_type_stars.php";
	showratings("F", (int)$_GET['thread_id']);
}

if ($rows != 0) {
	dbquery("UPDATE ".DB_THREADS." SET thread_postcount='$rows', thread_lastpostid='$last_post', thread_views=thread_views+1 WHERE thread_id='".(int)$_GET['thread_id']."'");
	if ($_GET['rowstart'] == 0 && $fdata['thread_poll'] == "1") {
		if (iMEMBER) {
			$presult = dbquery(
				"SELECT tfp.forum_poll_title, tfp.forum_poll_votes, tfv.forum_vote_user_id FROM ".DB_FORUM_POLLS." tfp
				LEFT JOIN ".DB_FORUM_POLL_VOTERS." tfv
				ON tfp.thread_id=tfv.thread_id AND forum_vote_user_id='".(int)$userdata['user_id']."'
				WHERE tfp.thread_id='".(int)$_GET['thread_id']."'"
			);
		} else {
			$presult = dbquery(
				"SELECT tfp.forum_poll_title, tfp.forum_poll_votes FROM ".DB_FORUM_POLLS." tfp
				WHERE tfp.thread_id='".(int)$_GET['thread_id']."'"
			);
		}
		if (dbrows($presult)) {
			$pdata = dbarray($presult); $i = 1;
			if (iMEMBER) { echo "<form name='voteform' method='post' action='".make_url(FUSION_SELF."?forum_id=".$fdata['forum_id']."&amp;thread_id=".$_GET['thread_id'], BASEDIR."forum-thread-".$_GET['thread_id']."-", $fdata['thread_subject'], ".html")."'>\n"; }
			echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border' style='margin-bottom:5px'>\n<tr>\n";
			echo "<td align='center' class='tbl2'><strong>".$pdata['forum_poll_title']."</strong></td>\n</tr>\n<tr>\n<td class='tbl1'>\n";
			echo "<table align='center' cellpadding='0' cellspacing='0'>\n";
			$presult = dbquery("SELECT forum_poll_option_text, forum_poll_option_votes
			FROM ".DB_FORUM_POLL_OPTIONS." WHERE thread_id='".(int)$_GET['thread_id']."' ORDER BY forum_poll_option_id ASC");
			$poll_options = dbrows($presult);
			while ($pvdata = dbarray($presult)) {
				if ((iMEMBER && isset($pdata['forum_vote_user_id']) || (!$fdata['forum_vote'] || !checkgroup($fdata['forum_vote'])))) {
					$option_votes = ($pdata['forum_poll_votes'] ? number_format(100 / $pdata['forum_poll_votes'] * $pvdata['forum_poll_option_votes']) : 0);
					echo "<tr>\n<td class='tbl1'>".$pvdata['forum_poll_option_text']."</td>\n";
					echo "<td class='tbl1'><img src='".get_image("pollbar")."' alt='".$pvdata['forum_poll_option_text']."' height='12' width='".(200 / 100 * $option_votes)."' class='poll' /></td>\n";
					echo "<td class='tbl1'>".$option_votes."%</td><td class='tbl1'>[".$pvdata['forum_poll_option_votes']." ".($pvdata['forum_poll_option_votes'] == 1 ? $locale['global_133'] : $locale['global_134'])."]</td>\n</tr>\n";
				} else {
					echo "<tr>\n<td class='tbl1'><label><input type='radio' name='poll_option' value='".$i."' style='vertical-align:middle' /> ".$pvdata['forum_poll_option_text']."</label></td>\n</tr>\n";
					$i++;
				}
			}
			if ((iMEMBER && isset($pdata['forum_vote_user_id']) || (!$fdata['forum_vote'] || !checkgroup($fdata['forum_vote'])))) {
				echo "<tr>\n<td align='center' colspan='4' class='tbl1'>".$locale['480']." : ".$pdata['forum_poll_votes']."</td>\n</tr>\n";
			} else {
				echo "<tr>\n<td class='tbl1'><input type='submit' name='cast_vote' value='".$locale['481']."' class='button' /></td>\n</tr>\n";
			}
			echo "</table>\n</td>\n</tr>\n</table>\n";
			if (iMEMBER) { echo "</form>\n"; }
		}
	}
	$result = dbquery(
		"SELECT p.thread_id, p.post_id, p.post_message, p.post_showsig, p.post_smileys, p.post_author, p.post_datestamp, p.post_ip, p.post_edituser, p.post_edittime,
		p.post_attachments,
		u.user_id, u.user_name, u.user_status, u.user_avatar, u.user_level, u.user_posts, u.user_groups, u.user_joined,".
		($user_field['user_sig'] ? " u.user_sig," : "").
		($user_field['user_trank'] ? " u.user_trank," : "").
		($user_field['user_web'] ? " u.user_web," : "").
		($user_field['user_location'] && $settings['forum_show_loc'] ? " u.user_location," : "").
		($user_field['user_birthdate'] && $settings['forum_show_age'] ? " u.user_birthdate," : "").
		($user_field['user_genderimage'] && $settings['forum_show_sex'] ? 
		" u.user_genderimage AS user_sex," : ($user_field['user_gendertext'] && $settings['forum_show_sex'] ? " u.user_gendertext AS user_sex," : "")).
		($settings['forum_show_onoff'] == "1" ? " u.user_lastvisit," : "")." 
		u2.user_name AS edit_name, u2.user_status AS edit_status
		FROM ".DB_POSTS." p
		LEFT JOIN ".DB_USERS." u ON p.post_author = u.user_id
		LEFT JOIN ".DB_USERS." u2 ON p.post_edituser = u2.user_id AND post_edituser > '0'
		WHERE p.thread_id='".(int)$_GET['thread_id']."' AND post_hidden='0'
		ORDER BY post_datestamp LIMIT ".(int)$_GET['rowstart'].",$posts_per_page"
	);
	if (iMOD) { echo "<form name='mod_form' method='post' action='".FORUM.FUSION_SELF."?thread_id=".(int)$_GET['thread_id']."&amp;page=".(int)$_GET['rowstart']."'>\n"; } // Pimped: constant FORUM added
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_thread_table'>\n";
	$numrows = dbrows($result);
	$current_row = 1;
	while ($data = dbarray($result)) {
		$message = $data['post_message'];
		if ($data['post_smileys']) { $message = parsesmileys($message); }
		if ($current_row == 1) {
			// Next and Previous Thread:
			if($prev != "" || $next != "") {
				echo "<tr><td colspan='2' class='forum-caption'>\n";
				echo "<div style='float: left;'>".$prev."</div>\n";
				echo "<div style='float: right;'>".$next."</div>\n";
				echo "</td></tr>";
			}
			echo "<tr>\n<td colspan='2' class='tbl2'>\n<div style='float:right' class='small'>";
			if (iMEMBER && $settings['thread_notify']) {
				if (dbcount("(thread_id)", DB_THREAD_NOTIFY, "thread_id='".(int)$_GET['thread_id']."' AND notify_user='".(int)$userdata['user_id']."'")) {
					$result2 = dbquery("UPDATE ".DB_THREAD_NOTIFY." SET notify_datestamp='".time()."', notify_status='1' WHERE thread_id='".$_GET['thread_id']."' AND notify_user='".$userdata['user_id']."'");
					echo "<a href='".FORUM."postify.php?post=off&amp;forum_id=".$fdata['forum_id']."&amp;thread_id=".$_GET['thread_id']."'>".$locale['515']."</a>"; // Pimped: added constant "FORUM"
				} else {
					echo "<a href='".FORUM."postify.php?post=on&amp;forum_id=".$fdata['forum_id']."&amp;thread_id=".$_GET['thread_id']."'>".$locale['516']."</a>"; // Pimped: added constant "FORUM"
				}
			}
			echo "&nbsp;<a href='".BASEDIR."print.php?type=F&amp;thread=".$_GET['thread_id']."&amp;page=".$_GET['page']."'><img src='".get_image("printer")."' alt='".$locale['519']."' title='".$locale['519']."' style='border:0;vertical-align:middle' /></a></div>\n";
			add_to_title($locale['global_201'].$fdata['thread_subject']);
			echo "<div style='position:absolute' class='forum_thread_title'><!--forum_thread_title--><strong>".$fdata['thread_subject']."</strong></div>\n</td>\n</tr>\n";
		}
		echo "<!--forum_thread_prepost_".$current_row."-->\n";
		// Advertising Bot
		if($settings['ads_vf_show']) {
			if(!$settings['ads_vf_display']) $settings['ads_vf_display'] = rand(1, $posts_per_page);
			
			if($current_row == $settings['ads_vf_display']) {
				ob_start();
				eval("?>".stripslashes($settings['ads_vf_code'])."<?php ");
				$contents = ob_get_contents();
				ob_end_clean();
				
				if ($current_row!=1) echo "<tr><td colspan='2' class='tbl1' style='height:10px'></td></tr>";
				echo "<tr>";
				echo "<td class='tbl2' style='width:140px'><strong>".$settings['ads_vf_name']."</strong></td>";
				echo "<td class='tbl2'><div class='small'>".$locale['fba_posted_on']." ".showdate("forumdate", time())."</div></td>";
				echo "</tr>\n<tr>";
				echo "<td valign='top' class='tbl2' style='width:140px'>";
				echo "<span class='small'>".$locale['fba_bot']."</span><br /><br />";
				echo "<span class='small'><strong>".$locale['fba_posts'].":</strong> n^x</span><br />";
				echo "<span class='small'><strong>".$locale['fba_joined'].":</strong> ".$locale['fba_join_date']."</span><br />";
				echo "<br /></td>";
				echo "<td valign='top' class='tbl1'>".$contents."</td>";
				echo "</tr>";
				echo "<tr>";
				echo "<td class='tbl2' style='width:140px;white-space:nowrap'>&nbsp;</td>";
				echo "<td class='tbl2'>&nbsp;</td>";
				echo "</tr>";
				if($current_row==1) echo "<tr>\n<td colspan='2' class='tbl1' style='height:10px'></td>\n</tr>\n";
			}
		}
		if ($current_row > 1) { echo "<tr>\n<td colspan='2' class='tbl1 forum_thread_post_space' style='height:10px'></td>\n</tr>\n"; }
		echo "<tr>\n<td class='tbl2 forum_thread_user_name' style='width:140px'><!--forum_thread_user_name-->".profile_link($data['user_id'], $data['user_name'], $data['user_status'])."</td>\n";
		echo "<td class='tbl2 forum_thread_post_date'>\n";
		echo "<div style='float:right' class='small'><a href='#post_".$data['post_id']."' name='post_".$data['post_id']."' id='post_".$data['post_id']."'>#".($current_row+$_GET['rowstart'])."</a>";
		// Pimped: Report Spam mod
		if ($settings['forum_report'] && !$fdata['thread_locked'] && $can_reply && $data['user_level'] < nMODERATOR) {
			echo "&nbsp;".report_spam($data['post_id'])."\n";
		}
		echo "&nbsp;<a href='".BASEDIR."print.php?type=F&amp;thread=".$_GET['thread_id']."&amp;post=".$data['post_id']."&amp;nr=".($current_row+$_GET['rowstart'])."'><img src='".get_image("printer")."' alt='".$locale['519a']."' title='".$locale['519a']."' style='border:0;vertical-align:middle' /></a></div>\n";
		echo "<div class='small'>".$locale['505'].showdate("forumdate", $data['post_datestamp'])."</div>\n";
		echo "</td>\n";
		echo "</tr>\n<tr>\n<td valign='top' class='tbl2 forum_thread_user_info' style='width:140px'>\n";
		// Pimped: Avatars for banned or suspended Users
		$banned = array(1, 3, 4);
		$cancel = array(5, 6);
		if (!in_array($data['user_status'], $banned) && !in_array($data['user_status'], $cancel) && $data['user_avatar'] && file_exists(IMAGES."avatars/".$data['user_avatar'])) {
			echo "<img src='".IMAGES."avatars/".$data['user_avatar']."' alt='".$locale['567']."' /><br /><br />\n";
		} elseif(!in_array($data['user_status'], $banned) && file_exists(IMAGES."avatars/noavatar.jpg")) {
			echo "<img src='".IMAGES."avatars/noavatar.jpg' alt='".$locale['567']."' /><br /><br />\n";
		} elseif(file_exists(IMAGES."avatars/banned.jpg")) {
		    echo "<img src='".IMAGES."avatars/banned.jpg' alt='".$locale['567']."' /><br /><br />\n";
		}
		// end
		
		if (array_key_exists("user_lastvisit", $data) && $data['user_lastvisit'] != "" && !in_array($data['user_status'], $banned)) { // Pimped: Online/Offline
			$lastseen = time() - $data['user_lastvisit'];
			if ($lastseen < 60) {
				$lastseen = get_image("online", $locale['503b'], "", $locale['503b'])."&nbsp;&nbsp;<span style='color:#".$settings['forum_show_onoff_color_on'].";'>".$locale['503b']."</span>";
			} elseif ($lastseen < 360)  {
				$lastseen = get_image("offline", $locale['503c'], "", $locale['503c'])."&nbsp;&nbsp;<span style='color:#".$settings['forum_show_onoff_color_re'].";'>".$locale['503c']."</span>";
			} else {
				$lastseen = get_image("offline", $locale['503d'], "", $locale['503d'])."&nbsp;&nbsp;<span style='color:#".$settings['forum_show_onoff_color_off'].";'>".$locale['503d']."</span>";
			}
			#echo "<span class='small'><strong>".$locale['503a']."</strong>  ".$lastseen."</span><br /><br />\n";
			echo "<strong>".$lastseen."</strong><br /><br />\n";
		}
		if (array_key_exists("user_trank", $data) && $data['user_trank'] != "") { // Pimped: User Team Rank
			echo $data['user_trank']."<br />\n";   
		}
		echo "<span class='small'>";
		if (in_array($data['user_status'], $banned)) {
			echo "<strong><span style='color:#FF0000;font-size:15px;'>".$locale['414']."</span></strong>";
		} elseif($data['user_level'] >= nMODERATOR) {
			echo $settings['forum_ranks'] ? show_forum_rank($data['user_posts'], $data['user_level']) : getuserlevel($data['user_level']);
		} else {
			$is_mod = false;
			foreach ($mod_groups as $mod_group) {
				if (!$is_mod && preg_match("(^\.{$mod_group}$|\.{$mod_group}\.|\.{$mod_group}$)", $data['user_groups'])) {
					$is_mod = true;
				}
			}
			if ($settings['forum_ranks']) {
				echo $is_mod ? show_forum_rank($data['user_posts'], nMODERATOR) : show_forum_rank($data['user_posts'], $data['user_level'], $data['user_groups']); // Pimped: Group Ranks
			} else {
				echo $is_mod ? $locale['userf1'] : getuserlevel($data['user_level']);
			}
		}
		echo "</span><br /><br />\n";
		echo "<!--forum_thread_user_info-->";
		echo "<span class='small'><strong>".$locale['502']."</strong> ".$data['user_posts']."</span><br />\n";
		echo "<span class='small'><strong>".$locale['504']."</strong> ".showdate("%d.%m.%y", $data['user_joined'])."</span><br />\n";
		
		if (array_key_exists("user_location", $data) && $data['user_location'] != "") {
			echo "<span class='small'><strong>".$locale['503']."</strong>  ".$data['user_location']."</span><br />\n";
		}
		
		if (array_key_exists("user_sex", $data) && $data['user_sex'] != "" && $data['user_sex'] >= 1) {
			echo "<span class='small'><strong>".$locale['503e']."</strong>  ".($data['user_sex'] == 1 ? $locale['503g'] : $locale['503f'])."</span><br />\n";
		}
		
		if (array_key_exists("user_birthdate", $data) && $data['user_birthdate'] != "" && $data['user_birthdate'] != "0000-00-00") {
			$birthdate = explode("-", $data['user_birthdate']);
			$age = date("Y") - $birthdate[0];
			$month = number_format($birthdate[1]);
			$day = number_format($birthdate[2]);
			$this_month = date("n");
			if($this_month == $month) {
				if(date("j") < $day) $age--;
			}elseif($this_month < $month) {
				$age--;
			}
			
			echo "<span class='small'><strong>".$locale['503h']."</strong>  ".$age."</span><br />\n";
		}

		if ($settings['warning_system']) { // Pimped: warning system
			$user_points = show_warning_points($data['user_id']);
			echo "<br /><span class='small'><a style='cursor:help;' onclick=\"warning_info();\">".$locale['WARN200']."</a></span><br />";
			echo warning_profile_link("2", $data['post_id'], $user_points);
		}
		echo "<br /></td>\n<td valign='top' class='tbl1 forum_thread_user_post'>\n";
		if (iMOD) { echo "<div style='float:right'><input type='checkbox' name='delete_post[]' value='".$data['post_id']."' /></div>\n"; }
		if (isset($_GET['highlight'])) {
			$words = explode(" ", urldecode($_GET['highlight']));
			$message = parseubb(highlight_words($words, $message));
		} else {
			$message = parseubb($message);
		}
		echo nl2br($message);
		echo "<!--sub_forum_post_message-->";

		if($data['post_attachments'] > 0) { // Pimped: Multi-Upload
			$att_result = dbquery("SELECT attach_id, attach_ext, attach_name, attach_counter
			FROM ".DB_FORUM_ATTACHMENTS." WHERE post_id='".(int)$data['post_id']."'");
			if (dbrows($att_result)) {
				$print_files = ''; $print_images = '';
				$count_files = 0; $count_images = 0;
				while ($att_data = dbarray($att_result)) {
					if (in_array($att_data['attach_ext'], $imagetypes) && file_exists(FORUM_ATT.$att_data['attach_name']) && @getimagesize(FORUM_ATT.$att_data['attach_name'])) {
						$print_images .= "\n".display_image($att_data['attach_name'])."<br />[".parsebytesize(filesize(FORUM_ATT.$att_data['attach_name']))."]<br /><br />\n";
						$count_images++;
					} elseif (file_exists(FORUM_ATT.$att_data['attach_name'])) {
						$print_files .= "\n<a href='".FORUM."file.php?getfile=".$att_data['attach_id']."'>".$att_data['attach_name']."</a>";
						$print_files .= " [".parsebytesize(filesize(FORUM_ATT.$att_data['attach_name']));
						$print_files .= ", ".sprintf($locale['507c'], $att_data['attach_counter'])."]<br />\n";
						
						$count_files++;
					}
				}
				if($print_files != '') {
					echo "\n<fieldset class='forum_attachments'>"; 
					echo "<legend class='forum_attachments_legend'>".
					profile_link($data['user_id'], $data['user_name'], $data['user_status']).($count_files > 1 ? $locale['507b'] : $locale['507'] )."</legend>"; 
					echo "<div class='forum_attachments_content'>".$print_files."</div>"; 
					echo "</fieldset>\n";
				}
				if($print_images != '') {
					if($print_files != '') echo "<br />";
					echo "\n<fieldset class='forum_attachments'>"; 
					echo "<legend class='forum_attachments_legend'>".
					profile_link($data['user_id'], $data['user_name'], $data['user_status']).($count_images > 1 ? $locale['506b'] : $locale['506'] )."</legend>";
					echo "<div class='forum_attachments_content'>".$print_images."</div>"; 
					echo "</fieldset>\n";
				}
			}
		}
		if ($data['post_edittime'] != "0") {
			echo "\n<fieldset class='forum_edit'>\n".$locale['508'].profile_link($data['post_edituser'], $data['edit_name'], $data['edit_status']).$locale['509'].showdate("forumdate", $data['post_edittime'])."</fieldset>"; // Pimped: added profile_link()
		}
		if ($data['post_showsig'] && isset($data['user_sig']) && $data['user_sig']) {
			echo "\n<fieldset class='forum_signatur'>
			<legend class='forum_signatur_legend'>".$locale['507d']."</legend>".nl2br(parseubb(parsesmileys($data['user_sig']), "b|i|u||center|small|url|mail|img|color"))."
			</fieldset>";
		}
		echo "<!--sub_forum_post--></td>\n</tr>\n";
		// Pimped: Post Ratings ->
		if($settings['forum_post_ratings']){
			$show = post_ratings_show($data['post_id']);
			$do = '';
			if(iMEMBER && $userdata['user_id'] !== $data['user_id']){
				$do .= "<span id='rb_".$data['post_id']."'>";
				$do .= post_ratings_do($data['post_id'], $userdata['user_id'], $data['post_author'], false);
				$do .= "</span>&nbsp;";
			}
			
			if($do != '' || $show != '') {
			echo "<tr>\n<td class='tbl2'>\n</td>\n<td class='tbl2'>";
			echo "<div align='left'>\n";
			echo $show;
			echo "</div>\n";
			echo "<div align='right'>\n";
			echo $do;
			echo "</div>\n";
			echo "</td></tr>";
			}
		}
		// <-
		echo "<tr>\n<td class='tbl2 forum_thread_ip' style='width:140px;white-space:nowrap'>";
		if (($settings['forum_ips'] && iMEMBER) || iMOD) { echo "<strong>".$locale['571']."</strong>: ".$data['post_ip']; } else { echo "&nbsp;"; }
		echo "</td>\n<td class='tbl2 forum_thread_userbar'>\n<div style='float:left;white-space:nowrap' class='small'><!--forum_thread_userbar-->\n";
		if (isset($data['user_web']) && $data['user_web']) {
			if (!strstr($data['user_web'], "http://")) { $urlprefix = "http://"; } else { $urlprefix = ""; }
			echo "<a href='".$urlprefix."".$data['user_web']."' target='_blank'><img src='".get_image("web")."' alt='".$data['user_web']."' style='border:0;vertical-align:middle' /></a> ";
		}
		if (iMEMBER && $userdata['user_id'] != $data['user_id'] && in_array($data['user_status'], $pif_global['can_recieve_pm'])) {
			echo "<a href='".BASEDIR."messages.php?msg_send=".$data['user_id']."'><img src='".get_image("pm")."' alt='".$locale['572']."' style='border:0;vertical-align:middle' /></a>\n";
		}
		echo "</div>\n<div style='float:right' class='small'>";
		if (iMEMBER && ($can_post || $can_reply)) {
			if (!$fdata['thread_locked']) {
				echo "<a href='".make_url(FORUM."post.php?action=reply&amp;forum_id=".(int)$fdata['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;post_id=".$data['post_id']."&amp;quote=".$data['post_id'], FORUM."post.php?action=reply&amp;forum_id=".(int)$fdata['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;post_id=".$data['post_id']."&amp;quote=".$data['post_id'], "", "")."'><img src='".get_image("quote")."' alt='".$locale['569']."' style='border:0px;vertical-align:middle' /></a>\n"; // Pimped: make_url, ohne seo url-rewrite
				if (iMOD || ($lock_edit && $last_post['post_id'] == $data['post_id'] && $userdata['user_id'] == $data['post_author']) || (!$lock_edit && $userdata['user_id'] == $data['post_author'])) {
					echo "<a href='".make_url(FORUM."post.php?action=edit&amp;forum_id=".(int)$fdata['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;post_id=".$data['post_id'], FORUM."post.php?action=edit&amp;forum_id=".(int)$fdata['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;post_id=".$data['post_id'], "", "")."'><img src='".get_image("forum_edit")."' alt='".$locale['568']."' style='border:0px;vertical-align:middle' /></a>\n"; // Pimped: make_url, ohne seo url-rewrite
				}
			} elseif(iMOD) {
				echo "<a href='".make_url(FORUM."post.php?action=edit&amp;forum_id=".(int)$fdata['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;post_id=".$data['post_id'], FORUM."post.php?action=edit&amp;forum_id=".(int)$fdata['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;post_id=".$data['post_id'], "", "")."'><img src='".get_image("forum_edit")."' alt='".$locale['568']."' style='border:0px;vertical-align:middle' /></a>\n"; // Pimped: make_url, ohne seo url-rewrite
			}
		}
		echo "</div>\n</td>\n</tr>\n";
		$current_row++;
	}
}

echo "</table><!--sub_forum_thread_table-->\n";

if (iMOD) {
	echo "<table cellspacing='0' cellpadding='0' width='100%'>\n<tr>\n<td style='padding-top:5px'>";
	echo "<a href='#' onclick=\"javascript:setChecked('mod_form','delete_post[]',1);return false;\">".$locale['460']."</a> ::\n";
	echo "<a href='#' onclick=\"javascript:setChecked('mod_form','delete_post[]',0);return false;\">".$locale['461']."</a></td>\n";
	echo "<td align='right' style='padding-top:5px'><input type='submit' name='move_posts' value='".$locale['517a']."' class='button' onclick=\"return confirm('".$locale['518a']."');\" />\n<input type='submit' name='delete_posts' value='".$locale['517']."' class='button' onclick=\"return confirm('".$locale['518']."');\" /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
}

// Pimped: Mark thread as resolved
if($fdata['forum_markresolved'] && !$fdata['thread_resolved'] && (iMOD || (iMEMBER && !$fdata['thread_locked'] && $fdata['thread_author'] == $userdata['user_id']))) {
	echo "<div align='left' style='padding-top:5px;padding-bottom:5px' class='forum_mark_resolved'>";
	echo "<form name='markform' method='post' action='".FORUM."options.php?step=resolved&amp;forum_id=".$fdata['forum_id']."&amp;thread_id=".$fdata['thread_id']."'>";
	echo "<input type='submit' name='mark_resolved' value='".$locale['529']."' class='button' />";
	echo "</form>";
	echo "</div>";
} elseif($fdata['forum_markresolved'] && $fdata['thread_resolved'] && iMOD) {
	echo "<div align='left' style='padding-top:5px;padding-bottom:5px' class='forum_mark_resolved'>";
	echo "<form name='markform' method='post' action='".FORUM."options.php?step=unsolved&amp;forum_id=".$fdata['forum_id']."&amp;thread_id=".$fdata['thread_id']."'>";
	echo "<input type='submit' name='mark_unsolved' value='".$locale['530']."' class='button' />";
	echo "</form>";
	echo "</div>";
}

if ($rows > $posts_per_page) {
	echo "<div align='center' style='padding-top:5px'>\n";
	echo pagination(true,$_GET['rowstart'],$posts_per_page,$rows,3,FUSION_SELF."?thread_id=".$_GET['thread_id'].(isset($_GET['highlight']) ? "&amp;highlight=".urlencode($_GET['highlight']):"")."&amp;", BASEDIR."forum-thread","-",$_GET['thread_id'],"-start","-",$fdata['thread_subject'])."\n"; // Pimped: Url-Rewrite
	echo "</div>\n";
}

$forum_list = ""; $current_cat = "";
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
	if(URL_REWRITE) { $value = $data['forum_id']."-".clean_subject_urlrewrite($data['forum_cat_name']); } else { $value = $data['forum_id']; } // Pimped for make_url (for javascript
	$sel = ($data['forum_id'] == $fdata['forum_id'] ? " selected='selected'" : "");  // Pimped: --> Forum-Cats and make_url (for javascript)
		$jump_list .= "<option value='".$value."'$sel>&nbsp;&nbsp;-".$data['forum_cat_name']."</option>\n";
	}
	return $jump_list;
}
$result = dbquery( // Pimped: Sub-Cats
	"SELECT f.forum_id, f.forum_name, f.forum_cat, f.forum_parent, f2.forum_name AS forum_cat_name
	FROM ".DB_FORUMS." f
	INNER JOIN ".DB_FORUMS." f2 ON f.forum_cat=f2.forum_id
	WHERE ".groupaccess('f.forum_access', $testaccess)." AND f.forum_cat!='0' AND f.forum_parent='0'
	ORDER BY f2.forum_order ASC, f.forum_order ASC"
);
while ($data = dbarray($result)) {
	if ($data['forum_cat_name'] != $current_cat) {
		if ($current_cat != "") { $forum_list .= "</optgroup>\n"; }
		$current_cat = $data['forum_cat_name'];
		$forum_list .= "<optgroup label='".$data['forum_cat_name']."'>\n";
	}
	$sel = ($data['forum_id'] == $fdata['forum_id'] ? " selected='selected'" : "");
	if(URL_REWRITE) { $value = $data['forum_id']."-".clean_subject_urlrewrite($data['forum_name']); } else { $value = $data['forum_id']; } // Pimped for make_url (for javascript)
	 // Pimped: Sub-Cats ->
	$forum_list .= "<option value='".$value."'$sel>".$data['forum_name']."</option>\n";
	$forum_list .= jump_to_forum($data['forum_id']);
	 // Pimped: Sub-Cats & for make_url (for javascript) <--
}
$forum_list .= "</optgroup>\n";
if (iMOD) {
	echo "<form name='modopts' method='post' action='".make_url(FORUM."options.php?forum_id=".$fdata['forum_id']."&amp;thread_id=".$_GET['thread_id'], FORUM."options.php?forum_id=".$fdata['forum_id']."&amp;thread_id=".$_GET['thread_id'], "", "")."'>\n";
} // Pimped: make_url, but no seo url-rewrite
echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
echo "<td style='padding-top:5px'>".$locale['540']."<br />\n";
echo "<select name='jump_id' class='textbox' onchange=\"jumpforum(this.options[this.selectedIndex].value);\">\n";
echo $forum_list."</select></td>\n";

if (iMOD) {
	echo "<td align='right' style='padding-top:5px'>\n";
	echo $locale['520']."<br />\n<select name='step' class='textbox'>\n";
	echo "<option value='none'>&nbsp;</option>\n";
	echo "<option value='renew'>".$locale['527']."</option>\n";
	echo "<option value='delete'>".$locale['521']."</option>\n";
	echo "<option value='".($fdata['thread_locked'] ? "unlock" : "lock")."'>".($fdata['thread_locked'] ? $locale['523'] : $locale['522'])."</option>\n";
	echo "<option value='".($fdata['thread_sticky'] ? "nonsticky" : "sticky")."'>".($fdata['thread_sticky'] ? $locale['525'] : $locale['524'])."</option>\n";
	echo "<option value='move'>".$locale['526']."</option>\n";
	echo "</select>\n<input type='submit' name='go' value='".$locale['528']."' class='button' />\n";
	echo "</td>\n";
}
echo "</tr>\n</table>\n"; if (iMOD) { echo "</form>\n"; }

if ($can_post || $can_reply) {
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td align='right' style='padding-top:10px'>\n<!--post_forum_buttons-->\n";
	if (!$fdata['thread_locked'] && $can_reply) {
		echo "<a href='".make_url(FORUM."post.php?action=reply&amp;forum_id=".$fdata['forum_id']."&amp;thread_id=".$_GET['thread_id'], FORUM."post.php?action=reply&amp;forum_id=".$fdata['forum_id']."&amp;thread_id=".$_GET['thread_id'], "", "")."'><img src='".get_image("reply")."' alt='".$locale['565']."' style='border:0px' /></a>\n"; // Pimped: make_url, but no seo url-rewrite
	}
	if ($can_post) {
		echo "<a href='".make_url(FORUM."post.php?action=newthread&amp;forum_id=".(int)$fdata['forum_id'], BASEDIR."forum-newthread-".(int)$fdata['forum_id'], "", ".html")."'><img src='".get_image("newthread")."' alt='".$locale['566']."' style='border:0px' /></a>\n"; // Pimped: make_url
	}
	echo "</td>\n</tr>\n</table>\n";
}
closetable();

// Tag System
if ($settings['enable_tags']) {
	require_once INCLUDES."tag_include.php";
	echo show_tags((int)$_GET['thread_id'], "F");
}

if ($can_reply && !$fdata['thread_locked']) {
	require_once INCLUDES."bbcode_include.php";
	opentable($locale['512']);
	echo "<form name='inputform' method='post' action='".FORUM."post.php?action=reply&amp;forum_id=".$fdata['forum_id']."&amp;thread_id=".$_GET['thread_id']."'>\n";
	echo "<table cellpadding='0' cellspacing='1' class='tbl-border center'>\n<tr>\n";
	echo "<td align='center' class='tbl1'><textarea name='message' cols='70' rows='7' class='textbox' style='width:98%'></textarea><br />\n";
	echo display_bbcodes("360px", "message")."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' class='tbl2'><label><input type='checkbox' name='disable_smileys' value='1' />".$locale['513']."</label>\n";
	if (array_key_exists("user_sig", $userdata) && $userdata['user_sig']) {
		echo "<br />\n<label><input type='checkbox' name='show_sig' value='1'/ checked='checked'>".$locale['513a']."</label>";
	}
	echo "</td></tr>\n<tr>\n";
	echo "<td align='center' class='tbl1'><input type='submit' name='postreply' value='".$locale['514']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form><!--sub_forum_thread-->\n";
	closetable();
} elseif($fdata['thread_locked']) {
	opentable($locale['564']);
	echo "<div align='center' class='forum_lockedthread_div'>";
	echo "<img src='".IMAGES."forum/denied.png' alt='".$locale['564']."' title='".$locale['564']."' style='vertical-align:middle;' />";
	echo "<br /><span style='font-weight: bold;' class='forum_lockedthread_msg'>".$locale['564']."!</span></div>\n";
	closetable();
}

echo "<script type='text/javascript'>function jumpforum(forumid) {\n";
echo "document.location.href='".make_url(FORUM."viewforum.php?forum_id='+forumid", BASEDIR."forum-'+forumid+'", "", ".html'").";\n"; // Pimped: make_url (for javascript)
echo "}\n"."function setChecked(frmName,chkName,val) {\n";
echo "dml=document.forms[frmName];\n"."len=dml.elements.length;\n"."for(i=0;i < len;i++) {\n";
echo "if(dml.elements[i].name == chkName) {\n"."dml.elements[i].checked = val;\n}\n}\n}\n";
echo "</script>\n";

// Similar Threads Panel
if($settings['forum_similar_threads']) {
	require_once FORUM_INC."forum_similar_threads.php";
}

// Share this thread
if($settings['sharethis_thread']) {
	$share_this = "thread";
	require_once INCLUDES."share_this_include.php";
}

require_once TEMPLATES."footer.php";
?>