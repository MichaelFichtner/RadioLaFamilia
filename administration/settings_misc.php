<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_misc.php
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
require_once TEMPLATES."admin_header.php";
include LOCALE.LOCALESET."admin/settings.php";

if (!checkrights("S6") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (!isset($_GET['page']) || !isnum($_GET['page'])) { $_GET['page'] = 1; }

if (isset($_GET['error']) && isnum($_GET['error']) && !isset($message)) {
	if ($_GET['error'] == 0) {
		$message = $locale['900'];
	} elseif ($_GET['error'] == 1) {
		$message = $locale['901'];
	}
	if (isset($message)) {
		echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; 
	}
}

$navigation = "<table width='100%' cellpadding='0' cellspacing='1' class='tbl-border'>\n<tr>\n";
$navigation .= "<td width='50%' align='center' class='".($_GET['page']==1?"tbl2":"tbl1")."'>".($_GET['page']==1?"<strong>":"")."<a href='".FUSION_SELF.$aidlink."&amp;page=1'>".$locale['400']." - ".$locale['650']."</a>".($_GET['page']==1?"</strong>":"")."</td>\n";
$navigation .= "<td width='50%' align='center' class='".($_GET['page']==2?"tbl2":"tbl1")."'>".($_GET['page']==2?"<strong>":"")."<a href='".FUSION_SELF.$aidlink."&amp;page=2'>".$locale['welpm100']."</a>".($_GET['page']==2?"</strong>":"")."</td>\n";
$navigation .= "</tr>\n</table>\n";
$navigation .= "<div style='margin:5px'></div>\n";

if ($_GET['page'] == 1) {

if (isset($_POST['savesettings'])) {
	$_POST['last_seen_users_color1'] = isset($_POST['last_seen_users_color1']) ? $_POST['last_seen_users_color1'] : $settings['last_seen_users_color1'];
	$_POST['last_seen_users_color2'] = isset($_POST['last_seen_users_color2']) ? $_POST['last_seen_users_color2'] : $settings['last_seen_users_color2'];
	$error = 0;
	if(!set_mainsetting('wysiwyg_enabled', stripinput($_POST['wysiwyg_enabled']))) { $error = 1; }
	if(!set_mainsetting('smtp_host', stripinput($_POST['smtp_host']))) { $error = 1; }
	if(!set_mainsetting('smtp_port', stripinput($_POST['smtp_port']))) { $error = 1; }
	if(!set_mainsetting('smtp_username', stripinput($_POST['smtp_username']))) { $error = 1; }
	if(!set_mainsetting('smtp_password', stripinput($_POST['smtp_password']))) { $error = 1; }
	if(!set_mainsetting('bad_words_enabled', isnum($_POST['bad_words_enabled']) ? $_POST['bad_words_enabled'] : "0")) { $error = 1; }
	if(!set_mainsetting('bad_words', addslash($_POST['bad_words']))) { $error = 1; }
	if(!set_mainsetting('bad_word_replace', stripinput($_POST['bad_word_replace']))) { $error = 1; }
	if(!set_mainsetting('guestposts', isnum($_POST['guestposts']) ? $_POST['guestposts'] : "0")) { $error = 1; }
	if(!set_mainsetting('enable_tags', isnum($_POST['enable_tags']) ? $_POST['enable_tags'] : "0")) { $error = 1; }
	if(!set_mainsetting('sharethis_news', isnum($_POST['sharethis_all']) ? $_POST['sharethis_all'] : "0")) { $error = 1; }
	if(!set_mainsetting('sharethis_article', isnum($_POST['sharethis_all']) ? $_POST['sharethis_all'] : "0")) { $error = 1; }
	if(!set_mainsetting('sharethis_thread', isnum($_POST['sharethis_all']) ? $_POST['sharethis_all'] : "0")) { $error = 1; }
	if(!set_mainsetting('comments_enabled', isnum($_POST['comments_enabled']) ? $_POST['comments_enabled'] : "0")) { $error = 1; }
	if(!set_mainsetting('showcomments_avatar', isnum($_POST['showcomments_avatar']) ? $_POST['showcomments_avatar'] : "0")) { $error = 1; }
	if(!set_mainsetting('shoutbox_showavatar', isnum($_POST['shoutbox_showavatar']) ? $_POST['shoutbox_showavatar'] : "0")) { $error = 1; }
	if(!set_mainsetting('ratings_enabled', isnum($_POST['ratings_enabled']) ? $_POST['ratings_enabled'] : "0")) { $error = 1; }
	if(!set_mainsetting('ratings_style', isnum($_POST['ratings_style']) ? $_POST['ratings_style'] : "0")) { $error = 1; }
	if(!set_mainsetting('visitorcounter_enabled', isnum($_POST['visitorcounter_enabled']) ? $_POST['visitorcounter_enabled'] : "0")) { $error = 1; }
	if(!set_mainsetting('last_seen_users_colors', isnum($_POST['last_seen_users_colors']) ? $_POST['last_seen_users_colors'] : "0")) { $error = 1; }
	if(!set_mainsetting('last_seen_users_color1', preg_match("/([0-9A-F]){6}/i",$_POST['last_seen_users_color1']) ? $_POST['last_seen_users_color1'] : "FFFFFF")) { $error = 1; }
	if(!set_mainsetting('last_seen_users_color2', preg_match("/([0-9A-F]){6}/i",$_POST['last_seen_users_color2']) ? $_POST['last_seen_users_color2'] : "FFFFFF")) { $error = 1; }
	log_admin_action("admin-4", "admin_settings_misc_save");
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

opentable($locale['400']);

echo $navigation;

if(is_dir(INCLUDES_JS."tiny_mce/")) { $tiny = true; } else { $tiny = false; };

echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='600' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['662']."<br />";
echo "<span class='small2'>".$locale['663']."<br />".$locale['663a']."<br /> ".$locale['663b']."<br /> ".$locale['663c']." <a href='http://pimped-fusion.net/forum-thread-285-tinymce-extension.html' target='_blank'>".$locale['663d']."</a></span></td>\n";
echo "<td width='50%' class='tbl'><select name='wysiwyg_enabled' class='textbox'>\n";
echo "<option value='0'".($settings['wysiwyg_enabled'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "<option value='ckeditor'".($settings['wysiwyg_enabled'] == "ckeditor" ? " selected='selected'" : "").">"."CK-Editor"."</option>\n"; // ...
if($tiny) {
echo "<option value='tinymce'".($settings['wysiwyg_enabled'] == "tinymce" ? " selected='selected'" : "").">"."Tiny-MCE"."</option>\n"; // ...
}
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['664']."<br /><span class='small2'>".$locale['665']."</span></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='smtp_host' value='".$settings['smtp_host']."' maxlength='200' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['674']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='smtp_port' value='".$settings['smtp_port']."' maxlength='10' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['666']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='smtp_username' value='".$settings['smtp_username']."' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['667']."</td>\n";
echo "<td width='50%' class='tbl'><input type='password' name='smtp_password' value='".$settings['smtp_password']."' maxlength='100' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['659']."</td>\n";
echo "<td width='50%' class='tbl'><select name='bad_words_enabled' class='textbox'>\n";
echo "<option value='1'".($settings['bad_words_enabled'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['bad_words_enabled'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['651']."<br /><span class='small2'>".$locale['652']."<br />".$locale['653']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='bad_words' cols='50' rows='5' class='textbox' style='width:200px;'>".$settings['bad_words']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['654']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='bad_word_replace' value='".$settings['bad_word_replace']."' maxlength='128' class='textbox' style='width:200px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['691']."</td>\n";
echo "<td width='50%' class='tbl'><select name='enable_tags' class='textbox'>\n";
echo "<option value='1'".($settings['enable_tags'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['enable_tags'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['692']."</td>\n";
echo "<td width='50%' class='tbl'><select name='sharethis_all' class='textbox'>\n";
echo "<option value='1'".($settings['sharethis_thread'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['sharethis_thread'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['655']."</td>\n";
echo "<td width='50%' class='tbl'><select name='guestposts' class='textbox'>\n";
echo "<option value='1'".($settings['guestposts'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['guestposts'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['671']."</td>\n";
echo "<td width='50%' class='tbl'><select name='comments_enabled' class='textbox'>\n";
echo "<option value='1'".($settings['comments_enabled'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['comments_enabled'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['683']."</td>\n";
echo "<td width='50%' class='tbl'><select name='showcomments_avatar' class='textbox'>\n";
echo "<option value='1'".($settings['showcomments_avatar'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['showcomments_avatar'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['683a']."</td>\n";
echo "<td width='50%' class='tbl'><select name='shoutbox_showavatar' class='textbox'>\n";
echo "<option value='1'".($settings['shoutbox_showavatar'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['shoutbox_showavatar'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['672']."</td>\n";
echo "<td width='50%' class='tbl'><select name='ratings_enabled' class='textbox'>\n";
echo "<option value='1'".($settings['ratings_enabled'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['ratings_enabled'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['680']."</td>\n";
echo "<td width='50%' class='tbl'><select name='ratings_style' class='textbox'>\n";
echo "<option value='1'".($settings['ratings_style'] == "1" ? " selected='selected'" : "").">".$locale['681']."</option>\n";
echo "<option value='0'".($settings['ratings_style'] == "0" ? " selected='selected'" : "").">".$locale['682']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['679']."</td>\n";
echo "<td width='50%' class='tbl'><select name='visitorcounter_enabled' class='textbox'>\n";
echo "<option value='1'".($settings['visitorcounter_enabled'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['visitorcounter_enabled'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['684']."</td>\n";
echo "<td width='50%' class='tbl'><select name='last_seen_users_colors' class='textbox' onchange=\"Color_Last_Seen_User_Panel(this);\">\n";
echo "<option value='1'".($settings['last_seen_users_colors'] == "1" ? " selected='selected'" : "").">".$locale['518']."</option>\n";
echo "<option value='0'".($settings['last_seen_users_colors'] == "0" ? " selected='selected'" : "").">".$locale['519']."</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'><div style='float:left'>".$locale['685']."<br /><span class='small'>".$locale['686']."</span></div><div id='preview_last_seen_users_color1' style='float:right;width:12px;height:12px;border:1px solid black;background-color:#".$settings['last_seen_users_color1'].";'>&nbsp;</div></td>\n";
echo "<td width='50%' class='tbl'>".color_mapper("last_seen_users_color1", $settings['last_seen_users_color1'], $settings['last_seen_users_colors'])."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'><div style='float:left'>".$locale['687']."<br /><span class='small'>".$locale['686']."</span></div><div id='preview_last_seen_users_color2' style='float:right;width:12px;height:12px;border:1px solid black;background-color:#".$settings['last_seen_users_color2'].";'>&nbsp;</div></td>\n";
echo "<td width='50%' class='tbl'>".color_mapper("last_seen_users_color2", $settings['last_seen_users_color2'], $settings['last_seen_users_colors'])."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
echo "<script type='text/javascript'>
function Color_Last_Seen_User_Panel(phtomrk) {
	if (phtomrk.value == 0) {
		document.forms['settingsform'].last_seen_users_color1.disabled = true;
		document.forms['settingsform'].last_seen_users_color2.disabled = true;		
	} else {
		document.forms['settingsform'].last_seen_users_color1.disabled = false;
		document.forms['settingsform'].last_seen_users_color2.disabled = false;		
	}
}
</script>\n";
closetable();

} elseif($_GET['page'] == 2) {

if (isset($_POST['savesettings'])) {
	$error = 0;
	if(!set_mainsetting('welcome_pm', ($_POST['welcome_pm'] == "1" ? "1" : "0"))) { $error = 1; }
	if(!set_mainsetting('welcome_pm_from', (isnum($_POST['welcome_pm_from']) ? $_POST['welcome_pm_from'] : "1"))) { $error = 1; }
	if(!set_mainsetting('welcome_pm_subject', $_POST['welcome_pm_subject'])) { $error = 1; }
	if(!set_mainsetting('welcome_pm_message', $_POST['welcome_pm_message'])) { $error = 1; }
	if(!set_mainsetting('welcome_pm_smiley', $_POST['welcome_pm_smiley'] == "n" ? "n" : "y")) { $error = 1; }
	log_admin_action("admin-4", "admin_settings_welcome_pm_save");
	redirect(FUSION_SELF.$aidlink."&error=".$error."&amp;page=2");
}

opentable($locale['welpm100']);

require_once INCLUDES."bbcode_include.php";

echo $navigation;

function create_opts($level, $label) {
global $settings;
$option = '';
$result = dbquery("SELECT user_id, user_name FROM ".DB_USERS." WHERE user_level='".(int)$level."' and user_status='0' ORDER BY user_name ASC");
	if(dbrows($result) != 0) {
		$option .= "<optgroup label='".$label."'>\n";
		while ($data = dbarray($result)) {
			$option .= "<option value='".$data['user_id']."'".($data['user_id'] == $settings['welcome_pm_from'] ? " selected='selected'" : "").">".$data['user_name']."</option>\n";
		}
		$option .= "</optgroup>\n";
	}
return $option;
}

$opt  = "<select name='welcome_pm_from' class='textbox' id='welcome_pm_from'>\n";
$opt .= create_opts(nSUPERADMIN, $locale['welpm106']);
$opt .= create_opts(nADMIN, $locale['welpm107']);
$opt .= create_opts(nMODERATOR, $locale['welpm108']);
$opt .= create_opts(nMEMBER, $locale['welpm109']);
$opt .= "</select>\n";

echo "<form method='post' name='settingsform' action='".FUSION_SELF.$aidlink."&amp;page=2'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n";
echo "<tr>\n";
echo "<td width='50%' class='tbl' colspan='2'><strong>".$locale['welpm101']."</strong></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='50%' class='tbl'><label for='welcome_pm'>".$locale['welpm102'].":</label></td>\n";
echo "<td width='50%' class='tbl'>
<select name='welcome_pm' class='textbox' id='welcome_pm'>\n
<option value='0'".($settings['welcome_pm'] == 0 ? " selected='selected'" : "").">".$locale['welpm104']."</option>\n
<option value='1'".($settings['welcome_pm'] == 1 ? " selected='selected'" : "").">".$locale['welpm103']."</option>\n
</select>\n
</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='50%' class='tbl'><label for='welcome_pm_from'>".$locale['welpm105'].":</label></td>\n";
echo "<td width='50%' class='tbl'>".$opt."</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='50%' class='tbl'><label for='welcome_pm_subject'>".$locale['welpm110'].":</label></td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='welcome_pm_subject' id='welcome_pm_subject' value='".$settings['welcome_pm_subject']."' class='textbox' style='width: 250px;' /></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='50%' class='tbl' valign='top'><label for='welcome_pm_message'>".$locale['welpm111'].":</label></td>\n";
echo "<td width='50%' class='tbl'>\n";
echo "<textarea name='welcome_pm_message' id='welcome_pm_message' class='textbox' rows='8' cols='55'>".$settings['welcome_pm_message']."</textarea><br />\n";
echo display_bbcodes("98%", "welcome_pm_message", "settingsform");
echo "</td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><label>
<input type='checkbox' name='welcome_pm_smiley' value='n'".($settings['welcome_pm_smiley'] == "n" ? " checked='checked'" : "")." />".$locale['welpm112']."</label></td>\n";
echo "</tr>\n";
echo "<tr>\n";
echo "<td width='50%' class='tbl'>".info_helper("welcome_message")."</td>";
echo "<td width='50%' class='tbl'><input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n";
echo "</table>\n";
echo "</form>\n";

closetable();

}

require_once TEMPLATES."footer.php";
?>