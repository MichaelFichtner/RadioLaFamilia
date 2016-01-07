<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: includes/cookie_include.php
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

if (isset($_POST['login']) && isset($_POST['user_name']) && isset($_POST['user_pass'])) {
	$user_name = preg_replace(array("/\=/","/\#/","/\sOR\s/"), "", stripinput($_POST['user_name']));
	$user_pass = encrypt_pw_part1($_POST['user_pass']);
	$result = dbquery("SELECT user_id, user_name, user_status, user_actiontime 
	FROM ".DB_USERS." WHERE user_name='".$user_name."' AND user_password='".encrypt_pw_part2($user_pass)."' LIMIT 1");
	if (dbrows($result)) {
		$data = dbarray($result);
		$cookie_value = $data['user_id'].".".$user_pass;
		if ($data['user_status'] == 0 && $data['user_actiontime'] == 0) {
			$cookie_exp = isset($_POST['remember_me']) ? time() + 3600 * 24 * 30 : time() + 3600 * 3;
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			setcookie(COOKIE_PREFIX."user", $cookie_value, $cookie_exp, "/", "", "0");
			redirect(BASEDIR."setuser.php?user=".$data['user_name'], true);
		} elseif ($data['user_status'] == 1) {
			redirect(BASEDIR."setuser.php?error=1&id=".$data['user_id'], true);
		} elseif ($data['user_status'] == 2) {
			redirect(BASEDIR."setuser.php?error=2", true);
		} elseif ($data['user_status'] == 3) {
			if ($data['user_actiontime'] < time()) {
				$cookie_exp = isset($_POST['remember_me']) ? time() + 3600 * 24 * 30 : time() + 3600 * 3;
				header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
				setcookie(COOKIE_PREFIX."user", $cookie_value, $cookie_exp, "/", "", "0");
				$result = dbquery("UPDATE ".DB_USERS." SET user_status='0', user_actiontime='0' WHERE user_id='".$data['user_id']."'");
				require_once INCLUDES."suspend_include.php";
				unsuspend_log($data['user_id'], 3, $locale['global_450'], true);
				// Send mail
				require_once INCLUDES."sendmail_include.php";
				$subject = $locale['global_453'];
				$message = str_replace("USER_NAME", $data['user_name'], $locale['global_452']);
				sendemail($data['user_name'], $data['user_email'], $settings['siteusername'], $settings['siteemail'], $subject, $message);
				// Send mail
				redirect(BASEDIR."setuser.php?user=".$data['user_name'], true);
			} else {
				redirect(BASEDIR."setuser.php?error=3&id=".$data['user_id'], true);
			}
		} elseif ($data['user_status'] == 4) {
			redirect(BASEDIR."setuser.php?error=4&id=".$data['user_id'], true);
		} elseif ($data['user_status'] == 5) {
			redirect(BASEDIR."setuser.php?error=5", true);
		} elseif ($data['user_status'] == 6) {
			redirect(BASEDIR."setuser.php?error=6", true);
		} elseif ($data['user_status'] == 7) {
			$cookie_exp = isset($_POST['remember_me']) ? time() + 3600 * 24 * 30 : time() + 3600 * 3;
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			setcookie(COOKIE_PREFIX."user", $cookie_value, $cookie_exp, "/", "", "0");
			$result = dbquery("UPDATE ".DB_USERS." SET user_status='0', user_actiontime='0' WHERE user_id='".$data['user_id']."'");
			// Send mail
			require_once INCLUDES."sendmail_include.php";
			$subject = $locale['global_454'];
			$message = str_replace("USER_NAME", $data['user_name'], $locale['global_455']);
			sendemail($data['user_name'], $data['user_email'], $settings['siteusername'], $settings['siteemail'], $subject, $message);
			// Send mail
			redirect(BASEDIR."setuser.php?user=".$data['user_name'], true);
		}
	} else {
		$result = dbquery("SELECT user_id FROM ".DB_USERS." WHERE user_name='".$user_name."' LIMIT 1"); // Pimped ->
		if(dbrows($result)) {
			$data = dbarray($result);
			dbquery("INSERT INTO ".DB_FAILED_LOGINS." (user_id, datestamp, logged_ip) VALUES ('".$data['user_id']."', '".time()."', '".USER_IP."')");
		} // Pimped <-
		redirect(BASEDIR."setuser.php?error=8");
	}
}

if (isset($_COOKIE[COOKIE_PREFIX.'user'])) {
	$cookie_vars = explode(".", $_COOKIE[COOKIE_PREFIX.'user']);
	$cookie_1 = isnum($cookie_vars['0']) ? $cookie_vars['0'] : "0";
	$cookie_2 = (preg_check("/^[0-9a-z]{32}$/", $cookie_vars['1']) ? $cookie_vars['1'] : "");
	$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$cookie_1."' AND user_password='".encrypt_pw_part2($cookie_2)."' LIMIT 1");
	unset($cookie_vars,$cookie_2); // Pimped
	if (dbrows($result)) {
		$userdata = dbarray($result);
		if ($userdata['user_status'] == 0) {
			if ($userdata['user_theme'] != "Default" && file_exists(THEMES.$userdata['user_theme']."/theme.php") && ($settings['userthemes'] == 1 || $userdata['user_level'] >= nADMIN)) {
				if (!theme_exists($userdata['user_theme'])) {
					echo "<strong>".$settings['sitename']." - ".$locale['global_300'].".</strong><br /><br />\n";
					echo $locale['global_301'];
					die();
				}
			} else {
				if (!theme_exists($settings['theme'])) {
					echo "<strong>".$settings['sitename']." - ".$locale['global_300'].".</strong><br /><br />\n";
					echo $locale['global_301'];
					die();
				}
			}
			if (isset($userdata['user_offset']) && !empty($userdata['user_offset'])) {
				$settings['timeoffset'] = $userdata['user_offset'];
			}
			if (!isset($_COOKIE[COOKIE_PREFIX.'lastvisit']) || !isnum($_COOKIE[COOKIE_PREFIX.'lastvisit'])) {
				$result = dbquery("UPDATE ".DB_USERS." SET user_threads='' WHERE user_id='".$userdata['user_id']."'");
				setcookie(COOKIE_PREFIX."lastvisit", $userdata['user_lastvisit'], time() + 3600, "/", "", "0");			
				$lastvisited = $userdata['user_lastvisit'];
			} else {
				$lastvisited = $_COOKIE[COOKIE_PREFIX.'lastvisit'];
			}
			if ($userdata['user_level'] > nMEMBER) {
				if (isset($_COOKIE[COOKIE_PREFIX.'admin']) && (!stristr(FUSION_REQUEST, str_replace("../", "", "/".ADMIN)) || USER_IP != $userdata['user_ip'])) {
					setcookie(COOKIE_PREFIX."admin", "", time() - 7200, "/", "", "0");
				}
			}
			// This language we will use, if we send a E-Mail to the User
			if($cookie_user_language && $cookie_user_language != $userdata['user_language']) {
				$res = dbquery("UPDATE ".DB_USERS." SET user_language='".$settings['locale']."' WHERE user_id='".$userdata['user_id']."'");
				if($res) $userdata['user_language'] = $cookie_user_language;
			}
			//
		} else {
			header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
			setcookie(COOKIE_PREFIX."user", "", time() - 7200, "/", "", "0");
			setcookie(COOKIE_PREFIX."lastvisit", "", time() - 7200, "/", "", "0");
			redirect(BASEDIR."index.php", true);
		}
	} else {
		$result = dbquery("SELECT user_id FROM ".DB_USERS." WHERE user_id='".$cookie_1."' LIMIT 1"); // Pimped ->
		if(dbrows($result)) {
			$data = dbarray($result);
			dbquery("INSERT INTO ".DB_FAILED_LOGINS." (user_id, datestamp, logged_ip) VALUES ('".$data['user_id']."', '".time()."', '".USER_IP."')");
		} // Pimped <-
		header("P3P: CP='NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM'");
		setcookie(COOKIE_PREFIX."user", "", time() - 7200, "/", "", "0");
		setcookie(COOKIE_PREFIX."lastvisit", "", time() - 7200, "/", "", "0");
		redirect(BASEDIR."index.php", true);
	}
unset($cookie_1); // Pimped
} else {
	if (!theme_exists($settings['theme'])) {
		echo "<strong>".$settings['sitename']." - ".$locale['global_300'].".</strong><br /><br />\n";
		echo $locale['global_301'];
		die();
	}
	$userdata = "";
	$userdata['user_level'] = 0;
	$userdata['user_rights'] = "";
	$userdata['user_groups'] = "";
}
?>