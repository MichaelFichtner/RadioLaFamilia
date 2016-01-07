<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: contact.php
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
include LOCALE.LOCALESET."contact.php";

add_to_title($locale['global_200'].$locale['400']);

$captcha = $settings['display_validation'] == "2" ? "2" : "1"; // Captcha
if($settings['recaptcha_publickey'] == "" || $settings['recaptcha_privatekey'] == "") { $captcha = "1"; }
if($captcha == "2") {
	require_once INCLUDES."recaptcha/recaptchalib.php";
	$resp = null;
	$recaptcha_error = null;
}

if (isset($_POST['sendmessage'])) {
	if($captcha == "1") { // Captcha
		include_once INCLUDES."securimage/securimage.php";
	}
	$error = "";
	$mailname = substr(stripinput(trim($_POST['mailname'])), 0, 50);
	$email = substr(stripinput(trim($_POST['email'])), 0, 100);
	$subject = substr(str_replace(array("\r","\n","@"), "", descript(stripslash(trim($_POST['subject'])))), 0, 50);
	$message = descript(stripslash(trim($_POST['message'])));
	if ($mailname == "") {
		$error .= "&middot; <span class='alt'>".$locale['420']."</span><br />\n";
	}
	if ($email == "" || !preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$error .= "&middot; <span class='alt'>".$locale['421']."</span><br />\n";
	}
	if ($subject == "") {
		$error .= "&middot; <span class='alt'>".$locale['422']."</span><br />\n";
	}
	if ($message == "") {
		$error .= "&middot; <span class='alt'>".$locale['423']."</span><br />\n";
	}

	if($captcha == "1") { // Captcha
		$securimage = new Securimage();
		if (!isset($_POST['captcha_code']) || $securimage->check($_POST['captcha_code']) == false) {
			$error .= "&middot; <span class='alt'>".$locale['424']."</span><br />\n";
		}
	} elseif($captcha == "2") {
		if ($_POST["recaptcha_response_field"]) { // Captcha
			$resp = recaptcha_check_answer($settings['recaptcha_privatekey'], $_SERVER["REMOTE_ADDR"],
			$_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
			if (!$resp->is_valid) {
				$recaptcha_error = $resp->error;
				if($recaptcha_error == "incorrect-captcha-sol") {
					$error .= "&middot; <span class='alt'>".$locale['recap102']."</span><br />\n";
				} else {
					$error .= "&middot; <span class='alt'>".$recaptcha_error."</span><br />\n";
				}
			}
		} else {
			$error .= "&middot; <span class='alt'>".$locale['recap103']."</span><br />\n";
		}
	}
	if (!$error) {
		require_once INCLUDES."sendmail_include.php";
		if (!sendemail($settings['siteusername'],$settings['siteemail'],$mailname,$email,$subject,$message)) {
			$error .= "&middot; <span class='alt'>".$locale['425']."</span><br />\n";
		}
	}
	if ($error) {
		opentable($locale['400']);
		echo "<div style='text-align:center'><br />\n".$locale['442']."<br /><br />\n".$error."<br />\n";
		echo "<a href='".make_url("contact.php", "contact", "", ".html")."'>".$locale['443']."</a></div><br />\n";
		closetable();
	} else {
		opentable($locale['400']);
		echo "<div style='text-align:center'><br />\n".$locale['440']."<br /><br />\n".$locale['441']."</div><br />\n";
		closetable();
	}
} else {
	opentable($locale['400']);
	
	ob_start();
	eval("?>".stripslashes($settings['contact_site'])."<?php ");
	$content = ob_get_contents();
	ob_end_clean();
	echo $content;
	echo "<br /><br />\n";
	echo "<form name='userform' method='post' action='".make_url("contact.php", "contact", "", ".html")."'>\n";
	echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
	echo "<td width='100' class='tbl'>".$locale['402']."</td>\n";
	echo "<td class='tbl'><input type='text' name='mailname' maxlength='50' class='textbox' style='width: 200px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='100' class='tbl'>".$locale['403']."</td>\n";
	echo "<td class='tbl'><input type='text' name='email' maxlength='100' class='textbox' style='width: 200px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='100' class='tbl'>".$locale['404']."</td>\n";
	echo "<td class='tbl'><input type='text' name='subject' maxlength='50' class='textbox' style='width: 200px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='100' class='tbl'>".$locale['405']."</td>\n";
	echo "<td class='tbl'><textarea name='message' rows='10' class='textbox' cols='50'></textarea></td>\n";
	echo "</tr>\n";
	if($captcha == "1") { // Captcha
		echo "<tr>\n";
		echo "<td width='100' class='tbl'>".$locale['407']."</td>\n";
		echo "<td class='tbl'>";
		echo "<img id='captcha' src='".INCLUDES."securimage/securimage_show.php' alt='' align='left' />\n";
		echo "<a href='".INCLUDES."securimage/securimage_play.php'><img src='".INCLUDES."securimage/images/audio_icon.gif' alt='' align='top' class='tbl-border' style='margin-bottom:1px' /></a><br />\n";
		echo "<a href='#' onclick=\"document.getElementById('captcha').src = '".INCLUDES."securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."securimage/images/refresh.gif' alt='' align='bottom' class='tbl-border' /></a>\n";
		echo "</td>\n</tr>\n<tr>";
		echo "<td class='tbl'>".$locale['408']."</td>\n";
		echo "<td class='tbl'><input type='text' name='captcha_code' class='textbox' style='width:100px' /></td>\n";
		echo "</tr>\n";
	} elseif($captcha == "2") {
		echo "<tr>\n<td valign='top' class='tbl'>".$locale['recap101']."</td>\n<td class='tbl'>";
		echo recaptcha_get_html($settings['recaptcha_publickey'], $recaptcha_error);
		echo "</td>\n</tr>\n";
	}
	echo "<tr>\n";
	echo "<td align='center' colspan='2' class='tbl'>\n";
	echo "<input type='submit' name='sendmessage' value='".$locale['406']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
	closetable();
}

require_once TEMPLATES."footer.php";
?>