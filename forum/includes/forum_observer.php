<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: forum/includes/forum_observer.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter, Matonor
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

function whoishere_observer($forum_place) {
global $userdata;

	$user_id = iMEMBER ? $userdata['user_id'] : USER_IP;
	$forum_id = "";
	$thread_id = "";

	if($forum_place == "index") {
		$forum_id = 0;
		$thread_id = 0;
	} elseif($forum_place == "forum" && isset($_GET['forum_id']) && isnum($_GET['forum_id'])) {
		$forum_id = $_GET['forum_id'];
		$thread_id = 0;
	} elseif($forum_place == "thread" && isset($_GET['thread_id']) && isnum($_GET['thread_id'])) {
		list($forum_id) = dbarraynum(dbquery("SELECT forum_id FROM ".DB_THREADS." WHERE thread_id='".(int)$_GET['thread_id']."'"));
		$thread_id = $_GET['thread_id'];
	}

	if(isnum($forum_id) && isnum($thread_id)) {
		dbquery("REPLACE INTO ".DB_FORUM_OBSERVER." SET user_id='".$user_id."', forum_id='".$forum_id."', thread_id='".$thread_id."', age='".time()."'");
	}
	dbquery("DELETE FROM ".DB_FORUM_OBSERVER." WHERE age < (".time()."-5*60)");

}

function whoishere_show($forum_place, $id, $tr=false) {
global $locale;
	if($forum_place == "index" || $forum_place == "forum") {
		$res = dbquery("SELECT ".DB_FORUM_OBSERVER.".user_id, forum_id, thread_id, user_name, user_level FROM ".DB_FORUM_OBSERVER."
			LEFT JOIN ".DB_USERS." ON ".DB_USERS.".user_id = ".DB_FORUM_OBSERVER.".user_id
			WHERE forum_id='".(int)$id."'");
		$guests = 0;
		$members = array();
		while($data = dbarray($res)) {
			if(empty($data['user_name'])) {
				$guests++;
			} else {
				$members[] = array("user_id" => $data['user_id'], "user_name" => $data['user_name'], "user_level" => $data['user_level']);
			}
		}
			$whoishere = user_list($guests, $members);
			if($forum_place == "index" && $whoishere) {
				echo "<span class='small'><strong>".$locale['wih100']."</strong> $whoishere</span><br />";
			} elseif($forum_place == "forum" && $whoishere) {
				if($tr) {
					echo "<td><strong>".$locale['wih100']."</strong> $whoishere</td>";
				} else {
					echo "<div style='padding: 5px;'><strong>".$locale['wih100']."</strong> $whoishere</div>";
				}
			}
	} elseif($forum_place == "thread") {
		$res = dbquery(
			"SELECT ".DB_FORUM_OBSERVER.".user_id, forum_id, thread_id, user_name, user_level FROM ".DB_FORUM_OBSERVER."
			LEFT JOIN ".DB_USERS." ON ".DB_USERS.".user_id = ".DB_FORUM_OBSERVER.".user_id
			WHERE thread_id='".(int)$_GET['thread_id']."'");
		$guests = 0;
		$members = array();
		while($data = dbarray($res)) {
			if(empty($data['user_name'])) {
				$guests++;
			} else {
				$members[] = array("user_id" => $data['user_id'], "user_name" => $data['user_name'], "user_level" => $data['user_level']);
			}
		}
		$whoishere = user_list($guests, $members);
		if($whoishere) {
			if($tr) {
				echo "<td>".$locale['wih100']." $whoishere</td>";
			} else {
				echo "<div style='padding: 5px;'>".$locale['wih100']." $whoishere</div>";
			}
		}
	}
}

?>