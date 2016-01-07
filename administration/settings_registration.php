<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_registration.php
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
require_once "../maincore.php";
require_once TEMPLATES."admin_header_editor.php";
include LOCALE.LOCALESET."admin/settings.php";

if (!checkrights("S4") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 1; }

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['900'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['901'];
	}
} elseif(isset($_GET['deleted']) && !isset($message)) {
	if ($_GET['deleted'] == "yes") {
		$message = $locale['576'];
	} else {
		$message = $locale['577'];
	}
} elseif(isset($_GET['saved']) && !isset($message)) {
	if ($_GET['saved'] == "yes") {
		$message = $locale['578'];
	} else {
		$message = $locale['579'];
	}
}

if (isset($message)) {
	echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
}

$settings2 = array();
$result = dbquery("SELECT settings_name, settings_value FROM ".DB_SETTINGS);
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}

// Navigation
$navigation  = "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
$navigation .= "<td width='50%' align='center' class='".($_GET['page'] == 1 ? "tbl2" : "tbl1")."'>";
$navigation .= ($_GET['page'] == 1 ? "<strong>":"")."<a href='".FUSION_SELF.$aidlink."&amp;page=1'>".$locale['400']."</a>".($_GET['page']==1?"</strong>":"");
$navigation .= "</td>\n";
$navigation .= "<td width='50%' align='center' class='".($_GET['page'] == 2 ? "tbl2" : "tbl1")."'>";
$navigation .= ($_GET['page'] == 2 ? "<strong>":"")."<a href='".FUSION_SELF.$aidlink."&amp;page=2'>".$locale['570']."</a>".($_GET['page']==2?"</strong>":"");
$navigation .= "</td>\n";
$navigation .= "</tr>\n</table>\n";
$navigation .= "<div style='margin:5px'></div>\n";

if ($_GET['page'] == 1) {

if($settings['wysiwyg_enabled'] == "ckeditor") {
	// nothing
} elseif($settings['wysiwyg_enabled'] == "tinymce") {
	echo "<script language='javascript' type='text/javascript'>advanced();</script>\n";
} else {
	require_once INCLUDES."html_buttons_include.php";
}

if (isset($_POST['savesettings'])) {
	$error = 0;
	
	if (addslash($_POST['license_agreement']) != $settings2['license_agreement']) {
		$license_lastupdate = time();
	} else {
		$license_lastupdate = $settings2['license_lastupdate'];
	}
	
	$license_agreement = addslash(preg_replace("(^<p>\s</p>$)", "", $_POST['license_agreement']));
	
	if(!set_mainsetting('enable_registration', isnum($_POST['enable_registration']) ? $_POST['enable_registration'] : "1")) { $error = 1; }
	if(!set_mainsetting('email_verification', isnum($_POST['email_verification']) ? $_POST['email_verification'] : "1")) { $error = 1; }
	if(!set_mainsetting('admin_activation', isnum($_POST['admin_activation']) ? $_POST['admin_activation'] : "0")) { $error = 1; }
	if(!set_mainsetting('registration_question', isnum($_POST['registration_question']) ? $_POST['registration_question'] : "0")) { $error = 1; }
	if(!set_mainsetting('display_validation', isnum($_POST['display_validation']) ? $_POST['display_validation'] : "1")) { $error = 1; }
	if(!set_mainsetting('recaptcha_publickey', $_POST['recaptcha_publickey'])) { $error = 1; }
	if(!set_mainsetting('recaptcha_privatekey', $_POST['recaptcha_privatekey'])) { $error = 1; }
	if(!set_mainsetting('login_method', ($_POST['login_method'] == "cookies" ? "cookies" : "sessions"))) { $error = 1; }
	if(!set_mainsetting('enable_terms', isnum($_POST['enable_terms']) ? $_POST['enable_terms'] : "0")) { $error = 1; }
	if(!set_mainsetting('license_agreement', $license_agreement)) { $error = 1; }
	if(!set_mainsetting('license_lastupdate', $license_lastupdate)) { $error = 1; }
	log_admin_action("admin-4", "admin_settings_registration_save");
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

opentable($locale['400']);

echo $navigation;

echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='650' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['551']."</td>\n";
echo "<td width='50%' class='tbl'><select name='enable_registration' class='textbox'>\n";
echo "<option value='1'".($settings2['enable_registration'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['enable_registration'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['552']."</td>\n";
echo "<td width='50%' class='tbl'><select name='email_verification' class='textbox'>\n";
echo "<option value='1'".($settings2['email_verification'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['email_verification'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['557']."</td>\n";
echo "<td width='50%' class='tbl'><select name='admin_activation' class='textbox'>\n";
echo "<option value='1'".($settings2['admin_activation'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['admin_activation'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['554']."</td>\n";
echo "<td width='50%' class='tbl'><select name='registration_question' class='textbox'>\n";
echo "<option value='1'".($settings2['registration_question'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['registration_question'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['553']."</td>\n";
echo "<td width='50%' class='tbl'><select name='display_validation' class='textbox'>\n";
echo "<option value='2'".($settings2['display_validation'] == "2" ? " selected='selected'" : "").">".$locale['580']."</option>\n";
echo "<option value='1'".($settings2['display_validation'] == "1" ? " selected='selected'" : "").">".$locale['581']."</option>\n";
echo "<option value='0'".($settings2['display_validation'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['582']."</td>\n"; // reCAPTCHA public key
echo "<td width='50%' class='tbl'><input type='text' name='recaptcha_publickey' value='".$settings2['recaptcha_publickey']."' size='60' maxlength='200' class='textbox' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['583']."</td>\n"; // reCAPTCHA private key
echo "<td width='50%' class='tbl'><input type='text' name='recaptcha_privatekey' value='".$settings2['recaptcha_privatekey']."' size='60' maxlength='200' class='textbox' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['560']."</td>\n";
echo "<td width='50%' class='tbl'><select name='login_method' class='textbox'>\n";
echo "<option value='cookies'".($settings2['login_method'] == "cookies" ? " selected='selected'" : "").">".$locale['561']."</option>\n";
echo "<option value='sessions'".($settings2['login_method'] == "sessions" ? " selected='selected'" : "").">".$locale['562']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['558']."</td>\n";
echo "<td width='50%' class='tbl'><select name='enable_terms' class='textbox'>\n";
echo "<option value='1'".($settings2['enable_terms'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings2['enable_terms'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl' colspan='2' align='center'>".$locale['559']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td class='tbl' colspan='2' align='center'><textarea name='license_agreement' cols='50' rows='10' class='textbox' style='width:320px'>".phpentities(stripslashes($settings2['license_agreement']))."</textarea></td>\n";
echo "</tr>\n";
if ($settings['wysiwyg_enabled'] != "ckeditor" && $settings['wysiwyg_enabled'] != "tinymce") {
	echo "<tr>\n<td class='tbl' colspan='2' align='center'>\n";
	echo display_html("settingsform", "license_agreement", true, true, true);
	echo "</td>\n</tr>\n";
}
echo "<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";

if($settings['wysiwyg_enabled'] == "ckeditor") {
	echo "<script type='text/javascript'>";
	echo "CKEDITOR.replace( 'license_agreement' );";
	echo "</script>";
}
		
closetable();


} elseif ($_GET['page'] == 2) {
// Page 2
opentable($locale['570']);

if(isset($_GET['del_id']) && isnum($_GET['del_id'])) {

	$result = dbquery("DELETE FROM ".DB_REGISTRATION." WHERE id='".(int)$_GET['del_id']."'");
	log_admin_action("admin-4", "admin_settings_registration_questions_deleted"); // Log Admin's Action

	if($result) {
		redirect(FUSION_SELF.$aidlink."&amp;page=2&amp;deleted=yes");
	} else {
		redirect(FUSION_SELF.$aidlink."&amp;page=2&amp;deleted=no");
	}

} elseif(isset($_GET['save_sr']) && stripinput($_POST['question']) != '' && stripinput($_POST['response']) != '') {

if(isset($_POST['edit_id']) && isnum($_POST['edit_id'])) {

	$result = dbquery("UPDATE ".DB_REGISTRATION." SET question = '".stripinput($_POST['question'])."', response = '".stripinput($_POST['response'])."'
	WHERE id = ".stripinput($_POST['edit_id']));
	log_admin_action("admin-4", "admin_settings_registration_questions_updated"); // Log Admin's Action

	if($result) {
		redirect(FUSION_SELF.$aidlink."&amp;page=2&amp;saved=yes");
	} else {
		redirect(FUSION_SELF.$aidlink."&amp;page=2&amp;saved=no");
	}

} else {

	$result = dbquery("INSERT INTO ".DB_REGISTRATION." (question, response) VALUES('".stripinput($_POST['question'])."', '".stripinput($_POST['response'])."')");
	log_admin_action("admin-4", "admin_settings_registration_questions_save"); // Log Admin's Action

	if($result) {
		redirect(FUSION_SELF.$aidlink."&amp;page=2&amp;saved=yes");
	} else {
		redirect(FUSION_SELF.$aidlink."&amp;page=2&amp;saved=no");
	}
}

}

echo $navigation;

if(isset($_GET['edit_sr']) && isnum($_GET['edit_sr']) ) {
	$result = dbquery("SELECT id, question, response FROM ".DB_REGISTRATION." WHERE id = ".(int)$_GET['edit_sr']);
	$data = dbarray($result);

	$question = $data['question'];
	$response = $data['response'];
	$hidden_input = "<input type='hidden' name='edit_id' value='".$data['id']."'/>";
} else {
	$question = '';
	$response = '';
	$hidden_input = '';
}

echo "<form action='".FUSION_SELF.$aidlink."&amp;page=2&amp;save_sr=1' method='post'>
<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n
<td class='tbl'>".$locale['571']."</td>
<td class='tbl'><input type='text' name='question' value='".$question."' size='40' maxlength='200' class='textbox' /></td>
</tr><tr>
<td class='tbl'>".$locale['572']."</td>
<td class='tbl'><input type='text' name='response' value='".$response."' size='40' maxlength='200' class='textbox' /></td>
</tr><tr>
<td class='tbl'></td>
<td class='tbl'>".$hidden_input."<input type='submit' value='".$locale['751']."' name='submit' class='button' /></td>
</tr></table>
</form>";

$result = dbquery("SELECT id, question, response FROM ".DB_REGISTRATION."");

if(dbrows($result)) {

echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n";
echo "<tr>";
echo "<td class='tbl'></td>";
echo "<td class='tbl'>".$locale['571']."</td>";
echo "<td class='tbl'>".$locale['572']."</td>";
echo "<td class='tbl'></td>";
echo "<td class='tbl'></td>";
echo "</tr>";

while($data = dbarray($result)) {
	echo "<tr>";
	echo "<td class='tbl'>".$data['id']."</td><td class='tbl'>".$data['question']."</td><td class='tbl'>".$data['response']."</td>";
	echo "<td class='tbl'><a href='".FUSION_SELF.$aidlink."&amp;page=2&amp;edit_sr=".$data['id']."'>".$locale['573']."</a></td>";
	echo "<td class='tbl'><a href='".FUSION_SELF.$aidlink."&amp;page=2&amp;del_id=".$data['id']."'>".$locale['574']."</a></td>";
	echo "</tr>";
}

echo "</table>";

} else {
	echo "<br />".$locale['575']."<br />";
}

closetable();

}

require_once TEMPLATES."footer.php";
?>