<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: themes/templates/footer.php
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
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

require_once INCLUDES."footer_includes.php";

define("CONTENT", ob_get_contents());
ob_end_clean();
render_page();
#print $group_access_debug;
#print "<pre>"; print_r($mysql_queries_time); print "</pre>";
echo "</body>\n</html>\n";

// Cron Job (6 MIN)
if ($settings['cronjob_minute'] < (time()-360)) {
	$result = dbquery("DELETE FROM ".DB_FLOOD_CONTROL." WHERE flood_timestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".DB_CAPTCHA." WHERE captcha_datestamp < '".(time()-360)."'");
	$result = dbquery("DELETE FROM ".DB_USERS." WHERE user_joined='0' AND user_ip='0.0.0.0' AND (user_level='".nADMIN."' OR user_level='".nSUPERADMIN."')");
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".time()."' WHERE settings_name='cronjob_minute'");
}

// Cron Job (1 HOUR)
if ($settings['cronjob_hour'] < (time()-3600)) { // TODO
	$time_now = time();

	$new_logs = dbcount("(log_id)", DB_FAILED_LOGINS, "datestamp > '".(int)$settings['cronjob_hour']."'");
	if($new_logs) {
	
		$result = dbquery(
			"SELECT COUNT(fl.log_id) AS tries, fl.user_id, fl.datestamp, MIN(fl.datestamp) AS mindate, MAX(fl.datestamp) AS maxdate,
			tu.user_language
			FROM ".DB_FAILED_LOGINS." fl
			LEFT JOIN ".DB_USERS." tu ON fl.user_id=tu.user_id
			WHERE datestamp > '".(int)$settings['cronjob_hour']."'
			GROUP BY user_id"
		);
	
		$reinc = false;
		
		while ($data = dbarray($result)) {
			if($data['user_language'] != "" && $data['user_language'] != $settings['locale'] && file_exists(LOCALE.$data['user_language']."/global.php")) {
				include LOCALE.$data['user_language']."/global.php";
				$reinc = true;
			}
			
			$message = sprintf($locale['flogins_101'], $data['tries']);
			if($data['tries'] == 1 ) {
				$message .= sprintf($locale['flogins_102'], showdate($settings['longdate'], $data['datestamp']));
			} else {
				$message .= sprintf($locale['flogins_103'], showdate($settings['longdate'], $data['mindate']), showdate($settings['longdate'], $data['maxdate']));
			}
			send_pm($data['user_id'], "0", $locale['flogins_100'], $message, "0");
		}
		if($reinc) include LOCALE.LOCALESET."global.php";
	}
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".(int)$time_now."' WHERE settings_name='cronjob_hour'");
}

// Cron Job (24 HOUR)
if ($settings['cronjob_day'] < (time()-86400)) {
	$new_time = time();
	
	$result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE notify_datestamp < '".(time()-1209600)."'");
	$result = dbquery("DELETE FROM ".DB_NEW_USERS." WHERE user_datestamp < '".(time()-86400)."'");

	// Check if there are any suspended users, and un-suspend them if the suspend-time is over
	$usr_inactive = dbcount("(user_id)", DB_USERS, "user_status='3' AND user_actiontime!='0' AND user_actiontime < '".time()."'");
	if ($usr_inactive) {
		require_once INCLUDES."sendmail_include.php";
		
		$result = dbquery(
			"SELECT user_id, user_name, user_email FROM ".DB_USERS." 
			WHERE user_status='3' AND user_actiontime!='0' AND user_actiontime < '".time()."'
			LIMIT 10"
		);
		while ($data = dbarray($result)) {
			$result2 = dbquery("UPDATE ".DB_USERS." SET user_status='0', user_actiontime='0' WHERE user_id='".(int)$data['user_id']."'");
			$subject = $locale['global_451'];
			$message = str_replace("USER_NAME", $data['user_name'], $locale['global_452']);
			$message = str_replace("LOST_PASSWORD", $settings['siteurl']."lostpassword.php", $message);
			sendemail($data['user_name'], $data['user_email'], $settings['siteusername'], $settings['siteemail'], $subject, $message);
		}
		if ($usr_inactive > 10) { $new_time = $settings['cronjob_day']; }
	}

	// Deactivate/Delete the Inactive User's Account if he did not respond to the E-Mail in time
	$usr_deactivate = dbcount("(user_id)", DB_USERS, "user_actiontime < '".time()."' AND user_actiontime!='0' AND user_status='7'");
	if ($usr_deactivate) {
		$result = dbquery(
			"SELECT user_id FROM ".DB_USERS." 
			WHERE user_actiontime < '".time()."' AND user_actiontime!='0' AND user_status='0'
			LIMIT 10"
		);
		if ($settings['deactivation_action'] == 0) {
			while ($data = dbarray($result)) {
				$result2 = dbquery("UPDATE ".DB_USERS." SET user_status='6', user_actiontime='0' WHERE user_id='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE notify_user='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_MESSAGES." WHERE message_to='".(int)$data['user_id']."' OR message_from='".(int)$data['user_id']."'");
			}
		} else {
			while ($data = dbarray($result)) {
				$result2 = dbquery("DELETE FROM ".DB_USERS." WHERE user_id='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_ARTICLES." WHERE article_name='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_name='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_MESSAGES." WHERE message_to='".(int)$data['user_id']."' OR message_from='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_NEWS." WHERE news_name='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_POLL_VOTES." WHERE vote_user='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_RATINGS." WHERE rating_user='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_SHOUTBOX." WHERE shout_name='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_SUSPENDS." WHERE suspended_user='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_THREADS." WHERE thread_author='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_POSTS." WHERE post_author='".(int)$data['user_id']."'");
				$result2 = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE notify_user='".(int)$data['user_id']."'");
			}
		}
		if ($usr_deactivate > 10) { $new_time = $settings['cronjob_day']; }
	}
	
// Pimped: Optimize Tables
if($new_time != $settings['cronjob_day']) { // Don't optimize if we already took a lot of resources
	$optimize_result = dbquery("SHOW TABLE STATUS");
	while($data = dbarray($optimize_result)) {
	   if ($data['Data_free']!=0) {
	      $result = dbquery("OPTIMIZE TABLE ".$data['Name']);
	   }
	}
}

	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value='".$new_time."' WHERE settings_name='cronjob_day'");
}

$output = ob_get_contents();
if (ob_get_length() !== FALSE){
	ob_end_clean();
}
echo handle_output($output);

$del = ob_get_status();
if (ob_get_length() !== FALSE && $del['del'] != ""){
	ob_end_flush();
}

if ($settings['login_method'] == "sessions") {
	session_write_close();
}

mysql_close($db_connect);
?>