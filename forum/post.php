<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/post.php
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
include LOCALE.LOCALESET."forum/post.php";

add_to_title($locale['global_204']);

require_once INCLUDES."forum_include.php";
require_once INCLUDES."bbcode_include.php";

if (!iMEMBER || !isset($_GET['forum_id']) || !isnum($_GET['forum_id'])) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url

if ($settings['forum_edit_lock'] == 1) {
	$lock_edit = true;
} else {
	$lock_edit = false;
}

$result = dbquery(
	"SELECT f.*, f2.forum_name AS forum_cat_name
	FROM ".DB_FORUMS." f
	LEFT JOIN ".DB_FORUMS." f2 ON f.forum_cat=f2.forum_id
	WHERE f.forum_id='".$_GET['forum_id']."' LIMIT 1"
);

if (dbrows($result)) {
	$fdata = dbarray($result);
	if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url
} else {
	redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
}

if (iMODERATOR) { define("iMOD", true); }
if (!defined("iMOD") && iMEMBER && $fdata['forum_moderators']) {
	$mod_groups = explode(".", $fdata['forum_moderators']);
	foreach ($mod_groups as $mod_group) {
		if (!defined("iMOD") && checkgroup($mod_group)) { define("iMOD", true);}
	}
}
if (!defined("iMOD")) { define("iMOD", false); }

$caption = $fdata['forum_cat_name']." &raquo; <a href='".make_url(FORUM."viewforum.php?forum_id=".$fdata['forum_id'], BASEDIR."forum-".$fdata['forum_id']."-", $fdata['forum_name'], ".html")."'>".$fdata['forum_name']."</a>";

if ((isset($_GET['action']) && $_GET['action'] == "newthread") && ($fdata['forum_post'] != 0 && checkgroup($fdata['forum_post']))) {
	include "postnewthread.php";
} elseif ((isset($_GET['action']) && $_GET['action'] == "reply") && ($fdata['forum_reply'] != 0 && checkgroup($fdata['forum_reply']))) {
	if (!isset($_GET['thread_id']) || !isnum($_GET['thread_id'])) {
		redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
	}

	$result = dbquery("SELECT * FROM ".DB_THREADS." WHERE thread_id='".$_GET['thread_id']."' AND forum_id='".$fdata['forum_id']."' AND thread_hidden='0'");
	
	if (dbrows($result)) {
		$tdata = dbarray($result);
	} else {
		redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
	}
	
	$caption .= " &raquo; ".$tdata['thread_subject'];
	
	if (!$tdata['thread_locked']) {
		include "postreply.php";
	} else {
		redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
	}	
} elseif (isset($_GET['action']) && $_GET['action'] == "edit" && isset($_GET['thread_id']) && isnum($_GET['thread_id']) && isset($_GET['post_id']) && isnum($_GET['post_id'])) {

	$result = dbquery("SELECT * FROM ".DB_THREADS." WHERE thread_id='".$_GET['thread_id']."' AND forum_id='".$fdata['forum_id']."' AND thread_hidden='0'");
	
	if (dbrows($result)) {
		$tdata = dbarray($result);
	} else {
		redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
	}

	$result = dbquery("SELECT tp.*, tt.thread_subject, ".($settings['forum_thread_description']? "tt.thread_description, " : "")."
	MIN(tp2.post_id) AS first_post
	FROM ".DB_POSTS." tp
	INNER JOIN ".DB_THREADS." tt on tp.thread_id=tt.thread_id
	INNER JOIN ".DB_POSTS." tp2 on tp.thread_id=tp2.thread_id
	WHERE tp.post_id='".$_GET['post_id']."' AND tp.thread_id='".$tdata['thread_id']."' AND tp.forum_id='".$fdata['forum_id']."' GROUP BY tp2.post_id");
	
	if (dbrows($result)) {
		$pdata = dbarray($result);
		$last_post = dbarray(dbquery("SELECT post_id FROM ".DB_POSTS." WHERE thread_id='".$_GET['thread_id']."' AND forum_id='".$_GET['forum_id']."' AND post_hidden='0' ORDER BY post_datestamp DESC LIMIT 1"));
	} else {
		redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
	}

	if ($userdata['user_id'] != $pdata['post_author'] && !iMOD) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url
	
	if (!$tdata['thread_locked'] && (($lock_edit && $last_post['post_id'] == $pdata['post_id'] && $userdata['user_id'] == $pdata['post_author']) || (!$lock_edit && $userdata['user_id'] == $pdata['post_author'])) ) {
		include "postedit.php";
	} elseif (iMOD) {
		include "postedit.php";
	} else {
		redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html"));
	}
} else {
	redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
}

require_once TEMPLATES."footer.php";
?>