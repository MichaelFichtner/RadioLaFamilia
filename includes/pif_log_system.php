<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: pif_log_system.php
| Version: Pimped Fusion v0.09.00
+----------------------------------------------------------------------------+
| Authors: slaughter
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/

// Log Admin's and Mod's Actions
function log_admin_action($cat, $action, $forum_id = "", $thread_id = "", $subject = "", $new_forum_id = "", $post_id = "", $new_thread_id = ""){
	global $userdata;
	
	$result = dbquery("INSERT INTO ".DB_ADMIN_LOG."
	(u_id, cat, forum_id, movedto_forum_id, thread_id, movedto_thread_id, post_id, action, subject, datestamp, log_ip)
	VALUES
	("._db($userdata['user_id']).", "._db($cat).", "._db($forum_id).", "._db($new_forum_id).", "._db($thread_id).", "._db($new_thread_id).",
	"._db($post_id).", "._db($action).", "._db($subject).", '".time()."', '".USER_IP."');");
}

// Log Registrations
function log_registration($username, $email, $log, $user_id = 0) {
	$action = isset($log['question_qu']) ? "##1#".$log['question_qu']."#".$log['response_qu']."" : "";
	$action .= isset($log['response_seci']) ? "##2#".$log['response_seci'].""  : "";
	$action .= isset($log['question_rec']) ? "##3#".$log['question_rec']."#".$log['response_rec'].""  : "";
	$subject = $username."#".$email;
	$result = dbquery("INSERT INTO ".DB_ADMIN_LOG." (u_id, cat, action, subject, datestamp, log_ip)
	VALUES
	("._db($user_id).", "._db("registration").", "._db($action).", "._db($subject).", '".time()."', '".USER_IP."');");
}

function log_registration_adduserid($id, $name, $email){

	$result = dbquery("UPDATE ".DB_ADMIN_LOG." SET u_id="._db($id)." WHERE subject="._db($name."#".$email)." AND u_id=''");

}

?>