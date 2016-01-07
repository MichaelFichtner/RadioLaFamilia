<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/options.php
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
include LOCALE.LOCALESET."forum/options.php";

if (!isset($_GET['thread_id']) || !isnum($_GET['thread_id'])) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url

if (iMEMBER) {
	if (!isset($_GET['forum_id']) || !isnum($_GET['forum_id'])) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url
		$result = dbquery("SELECT f.*, t.thread_id, t.thread_author, t.thread_locked, t.thread_resolved
		FROM ".DB_THREADS." t
		INNER JOIN ".DB_FORUMS." f ON t.forum_id=f.forum_id
		WHERE t.thread_id='".(int)$_GET['thread_id']."' LIMIT 1");
		if (dbrows($result)) {
			$data = dbarray($result);
			if (!checkgroup($data['forum_access'])) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url
			if (iMODERATOR) { define("iMOD", true); } 
			if (!defined("iMOD") && $data['forum_moderators']) {
			$mod_groups = explode(".", $data['forum_moderators']);
			foreach ($mod_groups as $mod_group) { 
				if (!defined("iMOD") && checkgroup($mod_group)) { define("iMOD", true); }
			} 
		} 
		if (!defined("iMOD")) { define("iMOD", false); }
	} else {
		redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
	} 
} else {
	redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html"));
	#define("iMOD", false); 
}

if (isset($_POST['step']) && $_POST['step'] != "") { $_GET['step'] = $_POST['step']; }


// Pimped: Mark thread as resolved
if (isset($_GET['step']) && $_GET['step'] == "resolved") {
	
	if($data['forum_markresolved'] && !$data['thread_resolved'] && (iMOD || (iMEMBER && !$data['thread_locked'] && $data['thread_author'] == $userdata['user_id']))) {
	
	$result = dbquery("UPDATE ".DB_THREADS." SET thread_resolved='1' WHERE thread_id='".(int)$_GET['thread_id']."'");
	
	opentable($locale['470']);
	echo "<div style='text-align:center'><br />\n".$locale['471']."<br /><br />\n";
	echo "<a href='".make_url(FORUM."viewforum.php?forum_id=".$_GET['forum_id'], BASEDIR."forum-".$_GET['forum_id']."-", $data['forum_name'], ".html")."'>".$locale['402']."</a><br /><br />\n";
	echo "<a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$locale['403']."</a><br /><br /></div>\n"; // Pimped: make_url
	closetable();
	
	} else {
		redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html"));
	}
	
} else {

// redirect Non-Mods
#if ((!iMOD && !iADMIN) || !checkgroup($data['forum_post'])) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url
if ((!iMOD) || !checkgroup($data['forum_post'])) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url

if (isset($_POST['canceldelete'])) { redirect(FORUM."viewthread.php?forum_id=".$_GET['forum_id']."&thread_id=".$_GET['thread_id']); } // Pimped: constant FORUM added

if (isset($_GET['step']) && $_GET['step'] == "unsolved") {
	if($data['forum_markresolved'] && $data['thread_resolved']) {
	$result = dbquery("UPDATE ".DB_THREADS." SET thread_resolved='0' WHERE thread_id='".(int)$_GET['thread_id']."'");
	opentable($locale['480']);
	echo "<div style='text-align:center'><br />\n".$locale['481']."<br /><br />\n";
	echo "<a href='".make_url(FORUM."viewforum.php?forum_id=".$_GET['forum_id'], BASEDIR."forum-".$_GET['forum_id']."-", $data['forum_name'], ".html")."'>".$locale['402']."</a><br /><br />\n";
	echo "<a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$locale['403']."</a><br /><br /></div>\n"; // Pimped: make_url
	closetable();
	} else {
		redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html"));
	}
} elseif (isset($_GET['step']) && $_GET['step'] == "renew") {
	$result = dbquery("
	SELECT p.post_id, p.post_author, p.post_datestamp FROM ".DB_POSTS." p 
	INNER JOIN ".DB_THREADS." t ON p.thread_id=t.thread_id
	WHERE p.thread_id='".$_GET['thread_id']."' AND t.thread_hidden='0' AND p.post_hidden='0'
	ORDER BY p.post_datestamp DESC LIMIT 1
	");
	if (dbrows($result)) {
		$data = dbarray($result);
		$result = dbquery("UPDATE ".DB_POSTS." SET post_datestamp='".time()."' WHERE post_id='".$data['post_id']."'");
		$result = dbquery("UPDATE ".DB_THREADS." SET thread_lastpost='".time()."', thread_lastpostid='".$data['post_id']."' WHERE thread_id='".$_GET['thread_id']."'");
		$result = dbquery("UPDATE ".DB_FORUMS." SET forum_lastpost='".time()."', forum_lastuser='".$data['post_author']."' WHERE forum_id='".$_GET['forum_id']."'");
		opentable($locale['458']);
		echo "<div style='text-align:center'><br />\n".$locale['459']."<br /><br />\n";
		echo "<a href='".FORUM."viewforum.php?forum_id=".$_GET['forum_id']."'>".$locale['402']."</a><br /><br />\n"; // the constant FORUM added
		echo "<a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$locale['403']."</a><br /><br /></div>\n"; // Pimped: make_url
		closetable();
		$result = dbquery("SELECT thread_subject FROM ".DB_THREADS." WHERE thread_id ='".$_GET['thread_id']."'");// pimped
		$data_2 = dbarray($result); // pimped
		log_admin_action("forum", $_GET['step'], $_GET['forum_id'], $_GET['thread_id'], $data_2['thread_subject']); // Pimped: Log mod's actions
	} else {
		redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
	}
} elseif (isset($_GET['step']) && $_GET['step'] == "delete") {
	opentable($locale['400']);
	echo "<div style='text-align:center'><br />\n";
	if (!isset($_POST['deletethread'])) {
		echo "<form name='delform' method='post' action='".FORUM.FUSION_SELF."?step=delete&amp;forum_id=".$_GET['forum_id']."&amp;thread_id=".$_GET['thread_id']."'>\n"; // Pimped: constant make_url added
		echo $locale['404']."<br /><br />\n";
		echo "<input type='submit' name='deletethread' value='".$locale['405']."' class='button' style='width:75px'>\n";
		echo "<input type='submit' name='canceldelete' value='".$locale['406']."' class='button' style='width:75px'><br /><br />\n";
		echo "</form>\n";
	} else {
		$result = dbquery("SELECT post_author, COUNT(post_id) as num_posts FROM ".DB_POSTS." WHERE thread_id='".$_GET['thread_id']."' GROUP BY post_author");
		if (dbrows($result)) {
			while ($pdata = dbarray($result)) {
				$result2 = dbquery("UPDATE ".DB_USERS." SET user_posts=user_posts-".$pdata['num_posts']." WHERE user_id='".$pdata['post_author']."'");
			}
		}
		
		$tdata = dbarray(dbquery("SELECT thread_id,thread_subject,thread_lastpost,thread_lastuser FROM ".DB_THREADS." WHERE thread_id='".$_GET['thread_id']."'")); //pimped

		$threads_count = dbcount("(forum_id)", DB_THREADS, "forum_id='".$_GET['forum_id']."'") - 1;
		$result = dbquery("DELETE FROM ".DB_POSTS." WHERE thread_id='".$_GET['thread_id']."'");
		$del_posts = mysql_affected_rows();
		$result = dbquery("DELETE FROM ".DB_THREADS." WHERE thread_id='".$_GET['thread_id']."'");
		$result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE thread_id='".$_GET['thread_id']."'");
		$result = dbquery("SELECT attach_name FROM ".DB_FORUM_ATTACHMENTS." WHERE thread_id='".$_GET['thread_id']."'");
		log_admin_action("forum", $_GET['step'], $_GET['forum_id'], $_GET['thread_id'], $tdata['thread_subject']); // Pimped: Log mod's actions
		if (dbrows($result) != 0) {
			while ($attach = dbarray($result)) {
				unlink(FORUM."attachments/".$attach['attach_name']);
			}
		}
		$result = dbquery("DELETE FROM ".DB_FORUM_ATTACHMENTS." WHERE thread_id='".$_GET['thread_id']."'");
		
		if ($threads_count > 0) {
			$result = dbquery("SELECT forum_id FROM ".DB_FORUMS." WHERE forum_id='".$_GET['forum_id']."' AND forum_lastpost='".$tdata['thread_lastpost']."' AND forum_lastuser='".$tdata['thread_lastuser']."'");
			if (dbrows($result)) {
				$result = dbquery("SELECT p.forum_id, p.post_author, p.post_datestamp FROM ".DB_POSTS." p 
				INNER JOIN ".DB_THREADS." t ON p.thread_id=t.thread_id
				WHERE p.forum_id='".$_GET['forum_id']."' AND t.thread_id='0' AND p.post_hidden='0'
				ORDER BY p.post_datestamp DESC LIMIT 1
				");
				$pdata = dbarray($result);
				$result = dbquery("UPDATE ".DB_FORUMS." SET forum_lastpost='".$pdata['post_datestamp']."', forum_postcount=forum_postcount-".$del_posts.", forum_threadcount=forum_threadcount-1, forum_lastuser='".$pdata['post_author']."' WHERE forum_id='".$_GET['forum_id']."'");
			}
		} else {
			$result = dbquery("UPDATE ".DB_FORUMS." SET forum_lastpost='0', forum_postcount=0, forum_threadcount=0, forum_lastuser='0' WHERE forum_id='".$_GET['forum_id']."'");
		}
		echo $locale['401']."<br /><br />\n";
		echo "<a href='".FORUM."viewforum.php?forum_id=".$_GET['forum_id']."'>".$locale['402']."</a><br /><br />\n"; // Pimped: constant FORUM added
		echo "<a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$locale['403']."</a><br /><br />\n"; // Pimped: make_url
	}
	echo "</div>\n";
	closetable();
} elseif (isset($_GET['step']) && $_GET['step'] == "lock") {
	$result = dbquery("UPDATE ".DB_THREADS." SET thread_locked='1' WHERE thread_id='".$_GET['thread_id']."' AND thread_hidden='0'");
	$result = dbquery("SELECT thread_subject FROM ".DB_THREADS." WHERE thread_id ='".$_GET['thread_id']."'"); // pimped
	$data = dbarray($result); // pimped
	log_admin_action("forum", $_GET['step'], $_GET['forum_id'], $_GET['thread_id'], $data['thread_subject']); // Pimped: Log mod's actions
	opentable($locale['410']);
	echo "<div style='text-align:center'><br />\n".$locale['411']."<br /><br />\n";
	echo "<a href='".FORUM."viewforum.php?forum_id=".$_GET['forum_id']."'>".$locale['402']."</a><br /><br />\n"; // Pimped: constant Forum added
	echo "<a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$locale['403']."</a><br /><br />\n</div>\n"; // Pimped: make_url
	closetable();
} elseif (isset($_GET['step']) && $_GET['step'] == "unlock") {
	$result = dbquery("UPDATE ".DB_THREADS." SET thread_locked='0' WHERE thread_id='".$_GET['thread_id']."' AND thread_hidden='0'");
	$result = dbquery("SELECT thread_subject FROM ".DB_THREADS." WHERE thread_id ='".$_GET['thread_id']."'"); // pimped
	$data = dbarray($result); // Pimped
	log_admin_action("forum", $_GET['step'], $_GET['forum_id'], $_GET['thread_id'], $data['thread_subject']); // Pimped: Log mod's actions
	opentable($locale['420']);
	echo "<div style='text-align:center'><br />\n".$locale['421']."<br /><br />\n";
	echo "<a href='".FORUM."viewforum.php?forum_id=".$_GET['forum_id']."'>".$locale['402']."</a><br /><br />\n"; // Pimped: constant FORUM added
	echo "<a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$locale['403']."</a><br /><br />\n</div>\n"; // Pimped: make_url
	closetable();
} elseif (isset($_GET['step']) && $_GET['step'] == "sticky") {
	$result = dbquery("UPDATE ".DB_THREADS." SET thread_sticky='1' WHERE thread_id='".$_GET['thread_id']."' AND thread_hidden='0'");
	$result = dbquery("SELECT thread_subject FROM ".DB_THREADS." WHERE thread_id ='".$_GET['thread_id']."'"); // pimped
	$data = dbarray($result); // Pimped
	log_admin_action("forum", $_GET['step'], $_GET['forum_id'], $_GET['thread_id'], $data['thread_subject']); // Pimped: Log mod's actions
	opentable($locale['430']);
	echo "<div style='text-align:center'><br />\n".$locale['431']."<br /><br />\n";
	echo "<a href='".FORUM."viewforum.php?forum_id=".$_GET['forum_id']."'>".$locale['402']."</a><br /><br />\n"; // Pimped: constant FORUM added
	echo "<a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$locale['403']."</a><br /><br />\n</div>\n"; // Pimped: make_url
	closetable();
} elseif (isset($_GET['step']) && $_GET['step'] == "nonsticky") {
	$result = dbquery("UPDATE ".DB_THREADS." SET thread_sticky='0' WHERE thread_id='".$_GET['thread_id']."' AND thread_hidden='0'");
	$result = dbquery("SELECT thread_subject FROM ".DB_THREADS." WHERE thread_id ='".$_GET['thread_id']."'"); // pimped
	$data = dbarray($result); // Pimped
	log_admin_action("forum", $_GET['step'], $_GET['forum_id'], $_GET['thread_id'], $data['thread_subject']); // Pimped: Log mod's actions
	opentable($locale['440']);
	echo "<div style='text-align:center'><br />".$locale['441']."<br /><br />\n";
	echo "<a href='".FORUM."viewforum.php?forum_id=".$_GET['forum_id']."'>".$locale['402']."</a><br /><br />\n"; // Pimped: constant FORUM added
	echo "<a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$locale['403']."</a><br /><br /></div>\n"; // Pimped: make_url
	closetable();
} elseif (isset($_GET['step']) && $_GET['step'] == "move") {
	opentable($locale['450']);
	if (isset($_POST['move_thread'])) {
		//die(var_dump($_POST));
		if (!isset($_POST['new_forum_id']) || !isnum($_POST['new_forum_id'])) { redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); } // Pimped: make_url
		
		if (!dbcount("(forum_id)", DB_FORUMS, "forum_id='".$_POST['new_forum_id']."'")) { redirect(make_url(BASEDIR."index.php", BASEDIR."index", "", ".html")); } // Pimped: make_url
		if (!dbcount("(thread_id)", DB_THREADS, "thread_id='".$_GET['thread_id']."' AND thread_hidden='0'")) { redirect(make_url(BASEDIR."index.php", BASEDIR."index", "", ".html")); } // Pimped: make_url
		
		$result_admin_log = dbquery("SELECT forum_id, thread_subject FROM ".DB_THREADS." WHERE thread_id ='".$_GET['thread_id']."'"); // pimped
		
		$result = dbquery("UPDATE ".DB_THREADS." SET forum_id='".$_POST['new_forum_id']."' WHERE thread_id='".$_GET['thread_id']."'");
		$result = dbquery("UPDATE ".DB_POSTS." SET forum_id='".$_POST['new_forum_id']."' WHERE thread_id='".$_GET['thread_id']."'");
		
		$post_count = dbcount("(post_id)", DB_POSTS, "thread_id='".$_GET['thread_id']."'");
		$data = dbarray($result_admin_log); // Pimped
		log_admin_action("forum", $_GET['step'], $data['forum_id'], $_GET['thread_id'], $data['thread_subject'], $_POST['new_forum_id']); // Pimped: Log mod's actions
		$result = dbquery("SELECT thread_lastpost, thread_lastuser FROM ".DB_THREADS." WHERE forum_id='".$_GET['forum_id']."' AND thread_hidden='0'
		ORDER BY thread_lastpost DESC LIMIT 1");
		if (dbrows($result)) {
			$pdata2 = dbarray($result);
			$result = dbquery("UPDATE ".DB_FORUMS." SET
			forum_lastpost='".$pdata2['thread_lastpost']."', forum_postcount=forum_postcount-".$post_count.", forum_threadcount=forum_threadcount-1, 
			forum_lastuser='".$pdata2['thread_lastuser']."'
			WHERE forum_id='".$_GET['forum_id']."'");
		} else {
			$result = dbquery("UPDATE ".DB_FORUMS." SET 
			forum_lastpost='0', forum_postcount=forum_postcount-".$post_count.", forum_threadcount=forum_threadcount-1, forum_lastuser='0'
			WHERE forum_id='".$_GET['forum_id']."'");
		}

		$result = dbquery("SELECT thread_lastpost, thread_lastuser
		FROM ".DB_THREADS."
		WHERE forum_id='".$_POST['new_forum_id']."' AND thread_hidden='0'
		ORDER BY thread_lastpost DESC LIMIT 1");
		if (dbrows($result)) {
			$pdata2 = dbarray($result);
			$result = dbquery("UPDATE ".DB_FORUMS." SET
			forum_lastpost='".$pdata2['thread_lastpost']."', forum_postcount=forum_postcount+".$post_count.", forum_threadcount=forum_threadcount+1,
			forum_lastuser='".$pdata2['thread_lastuser']."'
			WHERE forum_id='".$_POST['new_forum_id']."'");
		} else {
			$result = dbquery("UPDATE ".DB_FORUMS." SET
			forum_lastpost='0', forum_postcount=forum_postcount+1, forum_threadcount=forum_threadcount+".$post_count.", forum_lastuser='0'
			WHERE forum_id='".$_POST['new_forum_id']."'");
		}
		
		echo "<div style='text-align:center'><br />\n".$locale['452']."<br /><br />\n";
		echo "<a href='".make_url(FORUM."index.php", BASEDIR."forum", "", ".html")."'>".$locale['403']."</a><br /><br />\n</div>\n"; // Pimped: make_url
	} else {
		$move_list = ""; $sel = "";
		$result = dbquery("SELECT forum_id, forum_name FROM ".DB_FORUMS." WHERE forum_cat='0' ORDER BY forum_order");
		if (dbrows($result) != 0) {
			while ($data = dbarray($result)) {
				$result2 = dbquery("SELECT forum_id, forum_name FROM ".DB_FORUMS." WHERE forum_cat='".$data['forum_id']."' ORDER BY forum_order");
				if (dbrows($result2) != 0) {
					$move_list .= "<optgroup label='".$data['forum_name']."'>\n";
					while ($data2 = dbarray($result2)) {
						if ($_GET['forum_id'] == $data2['forum_id']) { $sel = " selected"; } else { $sel = ""; }
						$move_list .= "<option value='".$data2['forum_id']."'$sel>".$data2['forum_name']."</option>\n";
					}
					$move_list .= "</optgroup>\n";
				}
			}
		}
		echo "<form name='moveform' method='post' action='".FORUM.FUSION_SELF."?step=move&forum_id=".$_GET['forum_id']."&amp;thread_id=".$_GET['thread_id']."'>\n"; // Pimped: constant FORUM added
		echo "<table cellpadding='0' cellspacing='0' width='100%' class='tbl-border'>\n<tr>\n";
		echo "<td class='tbl2' width='150'>".$locale['451']."</td>\n";
		echo "<td class='tbl1'><select name='new_forum_id' class='textbox' style='width:250px;'>\n".$move_list."</select></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td colspan='2' class='tbl2' style='text-align:center;'><input type='submit' name='move_thread' value='".$locale['450']."' class='button' /></td>\n";
		echo "</tr>\n</table>\n</form>\n";
	}
	closetable();
} else {
	redirect(make_url(FORUM."index.php", BASEDIR."forum", "", ".html")); // Pimped: make_url
}

}

require_once TEMPLATES."footer.php";
?>