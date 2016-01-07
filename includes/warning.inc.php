<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: includes/warning.inc.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter, pirdani, emblinux
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/

// load locale
require_once LOCALE.LOCALESET."warning.php";

// javascript for popup
add_to_head('<script language="javascript" type="text/javascript">
<!--
var win=null; function warning_info(){ myleft=20;mytop=20; settings="width=500,height=400,top=" + mytop + ",left=" + myleft + ",scrollbars=yes,location=no,directories=no,status=no,menubar=no,toolbar=no,resizable=no,dependent=no"; win=window.open("'.FORUM.'includes/warning_info.php","Info",settings); win.focus(); } onerror = stopError; function stopError(){ return true; }
// -->
</script>');


function new_warning_post($post_or_userid, $subject, $message, $points, $KIND) {
	global $userdata, $settings, $locale;
	if($KIND == "Forum") {
		$new_warning_sql = dbquery("SELECT post_id, thread_id, forum_id, post_author FROM ".DB_POSTS." WHERE post_id='".$post_or_userid."'");
	}
	if($KIND == "Other" || dbrows($new_warning_sql) != 0) {
		if($KIND == "Forum") {
			$post_warn_data = dbarray($new_warning_sql);
			$warnuser_id = $post_warn_data['post_author'];
			$post = $post_or_userid;
			$threadid = $post_warn_data['thread_id'];
			$forumid = $post_warn_data['forum_id'];
		} else {
			$warnuser_id = $post_or_userid; $post = "0"; $threadid = "0"; $forumid = "0";
		}
		$insertdate = date("U");
		$sql = dbquery("INSERT INTO ".DB_WARNING."
		(warn_kind, user_id, post_id, thread_id, forum_id, warn_subject, warn_message, warn_point, warn_admin, warn_datestamp)
		VALUES
		("._db($KIND).", "._db($warnuser_id).", "._db($post).", "._db($threadid).", "._db($forumid).", "._db($subject).", "._db($message).",
		"._db($points).", "._db($userdata['user_id']).", "._db($insertdate).");");
		
		// We need to send a PM to the warned user
		if ($settings['warning_set_send_pm'] == 1) {
			$pn_subject = stripinput(trim($locale['WARN210']));
			if($KIND == "Forum") {
				$in = $locale['WARN211']." [url=".$settings['siteurl']."forum/viewthread.php?thread_id=".$post_warn_data['thread_id']."&pid=".$post_warn_data['post_id']."#post_".$post_warn_data['post_id']."]".GetPostTitle($post_warn_data['thread_id'])."[/url]\n";
			} else {
				$in = '';
			}

			$warning_subject = dbarray(dbquery("SELECT warn_subject FROM ".DB_WARNING_CATALOG." WHERE warn_id='".$subject."'"));
			
			$data_w = dbarray(dbquery("SELECT warn_length FROM ".DB_WARNING_CATALOG." WHERE warn_id='".(int)$subject."'"));
			$warning_length = $insertdate + ($data_w['warn_length'] * 86400);
			
			$pn_messages = stripinput(trim($in.$locale['WARN212']." ".$warning_subject['warn_subject']."
									".$locale['WARN213']." ".$message."
									".$locale['WARN214'].date($locale['WARN215'], $warning_length).$locale['WARN216']));
									
			if($settings['warning_set_pm_from'] == 0) {
				$pn_from = $userdata['user_id'];
			} else {
				$pn_from = $settings['warning_set_pm_from'];
			}
			  
			  $result = dbquery("INSERT INTO ".DB_MESSAGES." (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES ('".$warnuser_id."','".$pn_from."','".$pn_subject."','".$pn_messages."','y','0','".$insertdate."','0')");
		}
		// We need to send a PM to an Admin if User has more than 100 points
		if(show_warning_points($warnuser_id) >= 100) {
			$pn_subject = stripinput(trim($locale['WARN217']));
			$pn_messages = stripinput(trim("The member reached the limit:\n [url=".$settings['siteurl']."warning.php?lookup=".$warnuser_id."]".$locale['WARN218']."[/url]\n You should take some actions and suspend/bann this member!"));
			
			$result = dbquery("INSERT INTO ".DB_MESSAGES." (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES ('".$settings['warning_set_pm_to']."','"."0"."','".$pn_subject."','".$pn_messages."','y','0','".$insertdate."','0')");
		}
		return true;
	} else {
		return false;
	}
}


function warning_forum_link($postid) {
global $locale;
	$query = dbquery("SELECT p.forum_id as forum_id, p.post_id as post_id, p.thread_id as thread_id, t.thread_subject as subject FROM ".DB_POSTS." AS p
	                             LEFT JOIN ".DB_THREADS." AS t ON t.thread_id=p.thread_id WHERE post_id='".(int)$postid."'");
	if(dbrows($query)==1) {
		$data = dbarray($query);
		return "<a href='".make_url(FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['post_id'], BASEDIR."forum-thread-".$data['thread_id']."-pid".$data['post_id']."-", $data['subject'], ".html")."#post_".$data['post_id']."'>".$locale['WARN219']." ".$data['subject']."</a>";
	} else {
		return $locale['WARN220'];
	}
}

function GetPostTitle($thread_id) {
  $re = false;
	$query = dbquery("SELECT thread_subject FROM ".DB_THREADS." WHERE thread_id='".(int)$thread_id."'");
	if(dbrows($query)==1) {
		$data = dbarray($query);
		$re = $data['thread_subject'];
	}
  return $re;
}

function warning_user($user_id) {
	$data = dbarray(dbquery("SELECT user_name, user_status FROM ".DB_USERS." WHERE user_id='".(int)$user_id."'"));
	return profile_link($user_id, $data['user_name'], $data['user_status']);
}

function warning_points($warn_id) {
	$data = dbarray(dbquery("SELECT warn_point FROM ".DB_WARNING_CATALOG." WHERE warn_id='".(int)$warn_id."'"));
	return $data['warn_point'];
}

#function warning_length($warn_id, $datestamp) { #still used?
#	$data = dbarray(dbquery("SELECT warn_length FROM ".DB_WARNING_CATALOG." WHERE warn_id='".(int)$warn_id."'"));
#	return $datestamp + ($data['warn_length'] * 86400);
#}

// used in viewthread.php, Shoutbox, Comments, User Field
function show_warning_points($user_id) {
	$query = dbquery("SELECT warn_point, warn_subject, warn_datestamp FROM ".DB_WARNING." WHERE user_id=".(int)$user_id);
	$points = 0;
	while($pdata = dbarray($query)) {
		$wcdata = dbarray(dbquery("SELECT warn_length FROM ".DB_WARNING_CATALOG." WHERE warn_id='".(int)$pdata['warn_subject']."'"));
		if(($pdata['warn_datestamp']+($wcdata['warn_length']*86400)) > date("U")) {
			$points = $points + $pdata['warn_point'];
		}
	}
	if($points > 100) {
		$points = 100;
	}
	return $points;
}

// used in viewthread.php, Shoutbox, Comments, User Field
function warning_profile_link($type, $id, $points, $class = '', $style = '') {
	global $settings, $locale;

	$class = ($class ? " class='".$class."'" : '');
	$style = ($style ? " style='".$style."'" : '');
	
	$title = $points." ".($points == 1 ? $locale['WARN201'] : $locale['WARN202']);
	
	$url = ($type == "1" ? "warning.php?lookup=" : "warning.php?postid=");
	
	if ($settings['warning_set_visible'] == "1" || iMEMBER) {
		$link = "<a href='".BASEDIR.$url.$id."' title='".$title."' ".$class.$style.">".show_warning_symbols($points)."</a>";
	} else {
		$link = show_warning_symbols($points);
	}
	return $link;
}
###
function show_warning_symbols($punkte) {
if($punkte == 0) {
	$img = warning_image("green", "green", "green", "green");
} elseif($punkte <= 15) {
	$img = warning_image("green", "green", "green", "orange");
} elseif($punkte > 15 && $punkte <= 30) {
	$img = warning_image("green", "green", "orange", "orange");
} elseif($punkte > 30 && $punkte <= 45) {
	$img = warning_image("green", "orange", "orange", "orange");
} elseif($punkte > 45 && $punkte <= 60) {
	$img = warning_image("orange", "orange", "orange", "orange");
} elseif($punkte > 60 && $punkte <= 75) {
	$img = warning_image("orange", "orange", "orange", "red");
} elseif($punkte > 75 && $punkte <= 90) {
	$img = warning_image("orange", "orange", "red", "red");
} elseif($punkte > 90 && $punkte < 100) {
	$img = warning_image("orange", "red", "red", "red");
} elseif($punkte >= 100){
	$img = warning_image("red", "red", "red", "red");
}
return $img;
}

function warning_image($c1, $c2, $c3, $c4) {
	$re = '';
	$re .= "<img alt='warning' border='0' src='".IMAGES.$c1.".gif' />";
	$re .= "<img alt='warning' border='0' src='".IMAGES.$c2.".gif' />";
	$re .= "<img alt='warning' border='0' src='".IMAGES.$c3.".gif' />";
	$re .= "<img alt='warning' border='0' src='".IMAGES.$c4.".gif' />";
	return $re;
}

?>