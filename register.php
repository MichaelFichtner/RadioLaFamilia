<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: register.php
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
require_once "maincore.php";
require_once TEMPLATES."header.php";
include LOCALE.LOCALESET."register.php";
include LOCALE.LOCALESET."user_fields.php";

if (iMEMBER || !$settings['enable_registration']) { redirect("index.php"); }

if($settings['display_validation'] == "2" && ($settings['recaptcha_publickey'] == "" || $settings['recaptcha_privatekey'] == "")) { $settings['display_validation'] = "1"; }

if($settings['display_validation'] == "2") {
	require_once INCLUDES."recaptcha/recaptchalib.php";
	$resp = null;
	$recaptcha_error = null;
}

if (isset($_GET['activate'])) {
	if (!preg_check("/^[0-9a-z]{32}$/", $_GET['activate'])) { redirect("index.php"); }
	$result = dbquery("SELECT user_info FROM ".DB_NEW_USERS." WHERE user_code='".$_GET['activate']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		$user_info = unserialize($data['user_info']);
		$user_status = $settings['admin_activation'] == "1" ? "2" : "0";
				
		$profile_method = "validate_insert"; $db_fields = ""; $db_values = "";
		$result = dbquery(
			"SELECT tuf.field_name FROM ".DB_USER_FIELDS." tuf
			INNER JOIN ".DB_USER_FIELD_CATS." tufc ON tuf.field_cat = tufc.field_cat_id
			ORDER BY field_cat_order, field_order"
		);
		if (dbrows($result)) {
			while($data = dbarray($result)) {
				if (file_exists(LOCALE.LOCALESET."user_fields/".$data['field_name'].".php")) {
					include LOCALE.LOCALESET."user_fields/".$data['field_name'].".php";
				} elseif (file_exists(LOCALE."English/user_fields/".$data['field_name'].".php")) { // Pimped
					include LOCALE."English/user_fields/".$data['field_name'].".php";
				}
				if (file_exists(INCLUDES."user_fields/".$data['field_name']."_include.php")) {
					include INCLUDES."user_fields/".$data['field_name']."_include.php";
				}
			}
		}
		
		$result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_admin_password, user_email, user_hide_email, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status".$db_fields.") VALUES('".$user_info['user_name']."', '".$user_info['user_password']."', '', '".$user_info['user_email']."', '".$user_info['user_hide_email']."', '', '0', '0', '".time()."', '0', '".USER_IP."', '', '', '".nMEMBER."', '$user_status'".$db_values.")");
		$user_id = mysql_insert_id();
		if($settings['welcome_pm']) { // Pimped: Welcome PM
			send_pm($user_id, $settings['welcome_pm_from'], $settings['welcome_pm_subject'], $settings['welcome_pm_message'], $settings['welcome_pm_smiley']);
		}
		// Log User
		log_registration_adduserid($user_id, $user_info['user_name'], $user_info['user_email']);
		unset($user_id);
		//
		$result = dbquery("DELETE FROM ".DB_NEW_USERS." WHERE user_code='".$_GET['activate']."'");
		add_to_title($locale['global_200'].$locale['401']);
		opentable($locale['401']);
		if ($settings['admin_activation'] == "1") {
			echo "<div style='text-align:center'><br />\n".$locale['455']."<br /><br />\n".$locale['453']."<br /><br />\n</div>\n";
		} else {
			echo "<div style='text-align:center'><br />\n".$locale['455']."<br /><br />\n".$locale['452']."<br /><br />\n</div>\n";
		}
		closetable();
	} else {
		redirect("index.php");
	}
} elseif (isset($_POST['register'])) {
	if ($settings['display_validation'] == "1") {
		include_once INCLUDES."securimage/securimage.php";
	}
	$error = ""; $db_fields = ""; $db_values = "";
	$username = stripinput(trim(preg_replace("/ +/i", " ", $_POST['username'])));
	$email = stripinput(trim(preg_replace("/ +/i", "", $_POST['email'])));
	$password1 = stripinput(trim(preg_replace("/ +/i", "", $_POST['password1'])));
	
	if ($username == "" || $password1 == "" || $email == "") {
		$error .= "&middot; ".$locale['402']."<br />\n";
	}
	
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) {
		$error .= "&middot; ".$locale['403']."<br />\n";
	}
	
	if (preg_match("/^[0-9A-Z@]{6,20}$/i", $password1)) {
		if ($password1 != $_POST['password2']) $error .= "&middot; ".$locale['404']."<br />\n";
	} else {
		$error .= "&middot; ".$locale['405']."<br />\n";
	}
	
	if (!preg_match("/^[-0-9A-Z._%+ÄÖÜäöü]{1,50}@([-0-9A-Z.ÄÖÜäöü]+\.){1,50}([A-Z]){2,6}$/i", $email)) {
		$error .= "&middot; ".$locale['406']."<br />\n";
	}
	
	// Pimped: reCAPTCHA
	if($settings['display_validation'] == "2") {
		if ($_POST["recaptcha_response_field"]) {
			$resp = recaptcha_check_answer($settings['recaptcha_privatekey'], $_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) {
				$recaptcha_error = $resp->error;
				if($recaptcha_error == "incorrect-captcha-sol") {
					$error .= "&middot; ".$locale['553']."<br />\n";
				} else {
					$error .= "&middot; ".$recaptcha_error."<br />\n";
				}
			} else {
				#$log['question_rec'] = $_POST["recaptcha_challenge_field"];
				$log['response_rec'] = $_POST["recaptcha_response_field"];
			}
		} else {
			$error .= "&middot; ".$locale['554']."<br />\n";
		}
	}
	// <-
	
	// Pimped: Registration Questions
	if($settings['registration_question']) {
	$secure_re = isset($_POST['user_secure_question']) ? stripinput(trim($_POST['user_secure_question'])) : "";
	$secure_id = isset($_POST['user_secure_id']) ? stripinput(trim($_POST['user_secure_id'])) : "";

	if($secure_re == "" || $secure_id == "" || !isnum($secure_id)) {
		$error .= "&middot; ".$locale['412']."<br />\n";
	} else {

	$sec_result = dbquery("SELECT question, response FROM ".DB_REGISTRATION." WHERE id=".(int)$secure_id."");
	if (dbrows($sec_result)) {

		$sec_data = dbarray($sec_result);

		if (strtolower($sec_data['response']) != strtolower($secure_re)) {
			$error .= "&middot; ".$locale['413']."<br />\n";
		} else  {
			$log['question_qu'] = $sec_data['question'];
			$log['response_qu'] = $secure_re;
		}
		unset($sec_data);
	} else {
		$error .= "&middot; ".$locale['414']."<br />\n";
	}
	unset($sec_result);
	}
	}
	// <-
	$email_domain = substr(strrchr($email, "@"), 1);
	if (dbcount("(blacklist_id)", DB_BLACKLIST, "blacklist_email='$email' OR blacklist_email='$email_domain'") != 0) {
		$error = "&middot; ".$locale['411']."<br />\n";
	}
	
	if (dbcount("(user_id)", DB_USERS, "user_name='$username'") != 0) { $error = "&middot; ".$locale['407']."<br />\n";}
	
	if (dbcount("(user_id)", DB_USERS, "user_email='$email'") != 0) { $error = "&middot; ".$locale['408']."<br />\n";}
	
	if ($settings['email_verification'] == "1") {
		$result = dbquery("SELECT user_email, user_info FROM ".DB_NEW_USERS);
		while ($new_users = dbarray($result)) {
			$user_info = unserialize($new_users['user_info']); 
			if ($new_users['user_email'] == $email) { $error = "&middot; ".$locale['409']."<br />\n"; }
			if ($user_info['user_name'] == $username) { $error = "&middot; ".$locale['407']."<br />\n"; break; }
		}
	}
	
	if ($settings['display_validation'] == "1") {
		$securimage = new Securimage();
		if (!isset($_POST['captcha_code']) || $securimage->check($_POST['captcha_code']) == false) {
			$error .= "&middot; ".$locale['410']."<br />\n";
		} else {
			$log['response_seci'] = $_POST['captcha_code'];
		}
	}
	
	$user_hide_email = isnum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1";
	
	if ($settings['email_verification'] == "0") {
		$user_offset = isset($_POST['user_offset']) ? is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0" : "0";
		$profile_method = "validate_insert"; $db_fields = ""; $db_values = "";
		$result = dbquery(
			"SELECT tuf.field_name FROM ".DB_USER_FIELDS." tuf
			INNER JOIN ".DB_USER_FIELD_CATS." tufc ON tuf.field_cat = tufc.field_cat_id
			ORDER BY field_cat_order, field_order"
		);
		if (dbrows($result)) {
			while($data = dbarray($result)) {
				if(file_exists(LOCALE.LOCALESET."user_fields/".$data['field_name'].".php")) {
					include LOCALE.LOCALESET."user_fields/".$data['field_name'].".php";
				} elseif(file_exists(LOCALE."English/user_fields/".$data['field_name'].".php")) { // Pimped
					include LOCALE."English/user_fields/".$data['field_name'].".php";
				}
				if (file_exists(INCLUDES."user_fields/".$data['field_name']."_include.php")) {
					include INCLUDES."user_fields/".$data['field_name']."_include.php";
				}
			}
		}
	}
	
	if ($error == "") {
		if ($settings['email_verification'] == "1") {
			require_once INCLUDES."sendmail_include.php";
			mt_srand((double)microtime()*1000000); $salt = "";
			for ($i = 0; $i <= 7; $i++) { $salt .= chr(rand(97, 122)); }
			$user_code = md5($email.$salt);
			$activation_url = $settings['siteurl']."register.php?activate=".$user_code;
			if (sendemail($username,$email,$settings['siteusername'], $settings['siteemail'], $locale['449'], $locale['450'].$activation_url)) {
				$user_info = serialize(array(
					"user_name" => $username,
					"user_password" => encrypt_pw($password1),
					"user_email" => $email,
					"user_hide_email" => isnum($_POST['user_hide_email']) ? $_POST['user_hide_email'] : "1"
				));
				$result = dbquery("INSERT INTO ".DB_NEW_USERS." (user_code, user_email, user_datestamp, user_info) VALUES('$user_code', '".$email."', '".time()."', '$user_info')");
				// Log Registration + Security Question
				log_registration($username, $email, $log);
				opentable($locale['400']);
				echo "<div style='text-align:center'><br />\n".$locale['454']."<br /><br />\n</div>\n";
				closetable();
			} else {
				opentable($locale['456']);
				echo "<div style='text-align:center'><br />\n".$locale['457']."<br /><br />\n</div>\n";
				closetable();
			}
		} else {
			$user_status = $settings['admin_activation'] == "1" ? "2" : "0";
			$result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_admin_password, user_email, user_hide_email, user_avatar, user_posts, user_threads, user_joined, user_lastvisit, user_ip, user_rights, user_groups, user_level, user_status".$db_fields.") VALUES('$username', '".encrypt_pw($password1)."', '', '".$email."', '$user_hide_email', '', '0', '0', '".time()."', '0', '".USER_IP."', '', '', '".nMEMBER."', '$user_status'".$db_values.")");
			$user_id = mysql_insert_id();
			if($settings['welcome_pm']) { // Pimped: Welcome PM
				send_pm($user_id, $settings['welcome_pm_from'], $settings['welcome_pm_subject'], $settings['welcome_pm_message'], $settings['welcome_pm_smiley']);
			}
			// Log Registration + Security Question
			log_registration($username, $email, $log, $user_id);
			unset($user_id);
			
			opentable($locale['400']);
			if ($settings['admin_activation'] == "1") {
				echo "<div style='text-align:center'><br />\n".$locale['451']."<br /><br />\n".$locale['453']."<br /><br />\n</div>\n";
			} else {
				echo "<div style='text-align:center'><br />\n".$locale['451']."<br /><br />\n".$locale['452']."<br /><br />\n</div>\n";
			}
			closetable();
		}
	} else {
		opentable($locale['456']);
		echo "<div style='text-align:center'>
		<br />\n".$locale['458']."<br /><br />\n".$error."<br />\n<a href='".FUSION_SELF."'>".$locale['459']."</a>
		</div><br />\n";
		closetable();
	}
	unset($log);
} else {
	if ($settings['email_verification'] == "0") {
		$offset_list = "";
		for ($i = -13; $i < 17; $i++) {
			if ($i > 0) { $offset = "+".$i; } else { $offset = $i; }
			$offset_list .= "<option".($offset == "0" ? " selected='selected'" : "").">".$offset."</option>\n";
		}
	}
	opentable($locale['400']);
	echo "<div style='text-align:center'>".$locale['500']."\n";
	if ($settings['email_verification'] == "1") echo $locale['501']."\n";
	echo $locale['502'];
	if ($settings['email_verification'] == "1") echo "\n".$locale['503'];
	echo "</div><br />\n";
	echo "<form name='inputform' method='post' action='".FUSION_SELF."' onsubmit='return ValidateForm(this)'>\n";
	echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
	echo "<td class='tbl'>".$locale['u001']."<span style='color:#ff0000'>*</span></td>\n";
	echo "<td class='tbl'><input type='text' name='username' maxlength='30' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl'>".$locale['u002']."<span style='color:#ff0000'>*</span></td>\n";
	echo "<td class='tbl'><input type='password' name='password1' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl'>".$locale['u004']."<span style='color:#ff0000'>*</span></td>\n";
	echo "<td class='tbl'><input type='password' name='password2' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl'>".$locale['u005']."<span style='color:#ff0000'>*</span></td>\n";
	echo "<td class='tbl'><input type='text' name='email' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl'>".$locale['u006']."</td>\n";
	echo "<td class='tbl'><label><input type='radio' name='user_hide_email' value='1' checked='checked' />".$locale['u007']."</label>\n";
	echo "<label><input type='radio' name='user_hide_email' value='0' />".$locale['u008']."</label></td>\n";
	echo "</tr>\n";
	// reCAPTCHA ->
	if($settings['display_validation'] == "2") {
		echo "<tr>\n<td valign='top' class='tbl'>".$locale['511']."<span style='color:#ff0000'>*</span></td>\n<td class='tbl'>";
		echo recaptcha_get_html($settings['recaptcha_publickey'], $recaptcha_error);
		echo "</td>\n</tr>\n";
	// reCAPTCHA <-
	} elseif($settings['display_validation'] == "1") {
		echo "<tr>\n<td valign='top' class='tbl'>".$locale['504']."</td>\n<td class='tbl'>";
		echo "<img id='captcha' src='".INCLUDES."securimage/securimage_show.php' alt='".$locale['504']."' align='left' />\n";
    echo "<a href='".INCLUDES."securimage/securimage_play.php'><img src='".INCLUDES."securimage/images/audio_icon.gif' alt='' align='top' class='tbl-border' style='margin-bottom:1px' /></a><br />\n";
    echo "<a href='#' onclick=\"document.getElementById('captcha').src = '".INCLUDES."securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."securimage/images/refresh.gif' alt='' align='bottom' class='tbl-border' /></a>\n";
		echo "</td>\n</tr>\n<tr>";
		echo "<td class='tbl'>".$locale['505']."<span style='color:#ff0000'>*</span></td>\n";
		echo "<td class='tbl'><input type='text' name='captcha_code' class='textbox' style='width:100px' /></td>\n";
		echo "</tr>\n";
	}
	
	// Pimped: Register Questions ->
	if($settings['registration_question']) {
	$reg_result = dbquery("SELECT id, question FROM ".DB_REGISTRATION." ORDER BY RAND() LIMIT 1");
	$reg_data = dbarray($reg_result);
	echo "<tr>\n";
	echo "<td class='tbl'>".$locale['510']."<span style='color:#ff0000'>*</span><br />\n".$reg_data['question']."</td>\n";
	echo "<td class='tbl'><input type='text' name='user_secure_question' maxlength='30' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n";
	echo "<input type='hidden' name='user_secure_id' value='".$reg_data['id']."'>";
	unset($reg_data);
	unset($reg_result);
	}
	// <-
	if ($settings['email_verification'] == "0") {
		$profile_method = "input"; $icu = 0; $user_cats = array(); $user_fields = array(); $ob_active = false; $register = true;
		$result2 = dbquery(
			"SELECT tuf.field_name, tuf.field_cat, tufc.field_cat_name FROM ".DB_USER_FIELDS." tuf
			INNER JOIN ".DB_USER_FIELD_CATS." tufc ON tuf.field_cat = tufc.field_cat_id
			ORDER BY field_cat_order, field_order"
		);
		if (dbrows($result2)) {
			while($data2 = dbarray($result2)) {
				if ($icu != $data2['field_cat']) {
					if ($ob_active) {
						$user_fields[$icu] = ob_get_contents();
						ob_end_clean();
						$ob_active = false;
					}
					$icu = $data2['field_cat'];
					$user_cats[] = array(
						"field_cat_name" => $data2['field_cat_name'],
						"field_cat" => $data2['field_cat']
					);
				}
				if (!$ob_active) {
					ob_start();
					$ob_active = true;
				}
				if (file_exists(LOCALE.LOCALESET."user_fields/".$data2['field_name'].".php")) {
					include LOCALE.LOCALESET."user_fields/".$data2['field_name'].".php";
				} elseif (file_exists(LOCALE."English/user_fields/".$data2['field_name'].".php")) {
					include LOCALE."English/user_fields/".$data2['field_name'].".php";
				}
				if (file_exists(INCLUDES."user_fields/".$data2['field_name']."_include.php")) {
					include INCLUDES."user_fields/".$data2['field_name']."_include.php";
				}
			}
		}
		
		if ($ob_active) {
			$user_fields[$icu] = ob_get_contents();
			ob_end_clean();
		}
		foreach ($user_cats as $category) {
			if (array_key_exists($category['field_cat'], $user_fields) && $user_fields[$category['field_cat']]) {
				echo "<tr>\n";
				echo "<td colspan='2' class='tbl2'><strong>".$category['field_cat_name']."</strong></td>\n";
				echo "</tr>\n".$user_fields[$category['field_cat']];
			}
		}
	}
	
	if ($settings['enable_terms'] == 1) {
		echo "<tr>\n<td class='tbl'>".$locale['508'] ."<span style='color:#ff0000'>*</span></td>\n";
		echo "<td class='tbl'><input type='checkbox' id='agreement' name='agreement' value='1' onclick='checkagreement()' /> <span class='small'><label for='agreement'>".$locale['509'] ."</label></span></td>\n";
		echo "</tr>\n";
	}
	echo "<tr>\n<td align='center' colspan='2'><br />\n";
	echo "<input type='submit' name='register' value='".$locale['506']."' class='button'".($settings['enable_terms'] == 1 ? " disabled='disabled'" : "")." />\n";
	echo "</td>\n</tr>\n</table>\n</form>\n";
	closetable();
	echo "<script type='text/javascript'>
function ValidateForm(frm) {
	if (frm.username.value==\"\") {
		alert(".escape_javascript($locale['550']).");
		return false;
	}
	if (frm.password1.value==\"\") {
		alert(".escape_javascript($locale['551']).");
		return false;
	}
	if (frm.email.value==\"\") {
		alert(".escape_javascript($locale['552']).");
		return false;
	}
}
</script>\n"; // Pimped: JavaScript

	if ($settings['enable_terms'] == 1) {
		echo "<script language='JavaScript' type='text/javascript'>
			function checkagreement() {
				if(document.inputform.agreement.checked) {
					document.inputform.register.disabled=false;
				} else {
					document.inputform.register.disabled=true;
				}
			}
		</script>";
	}
}

require_once TEMPLATES."footer.php";
?>