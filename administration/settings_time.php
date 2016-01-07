<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: settings_time.php
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

if (!checkrights("S2") || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

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

if (isset($_POST['savesettings'])) {
	$error = 0;
	if(!set_mainsetting('shortdate', $_POST['shortdate'])) { $error = 1; }
	if(!set_mainsetting('longdate', $_POST['longdate'])) { $error = 1; }
	if(!set_mainsetting('forumdate', $_POST['forumdate'])) { $error = 1; }
	if(!set_mainsetting('newsdate', $_POST['newsdate'])) { $error = 1; }
	if(!set_mainsetting('subheaderdate', $_POST['subheaderdate'])) { $error = 1; }
	if(!set_mainsetting('timeoffset', $_POST['timeoffset'])) { $error = 1; }
	log_admin_action("admin-4", "admin_settings_time_save");
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

$settings2 = array();
$result = dbquery("SELECT settings_name, settings_value FROM ".DB_SETTINGS); // Get original Settings to fix bug with Time Offset
while ($data = dbarray($result)) {
	$settings2[$data['settings_name']] = $data['settings_value'];
}

$offsetlist = '
<option value="-12.0"'.($settings2['timeoffset'] == "-12.0" ? " selected" : "").'>(GMT -12:00) Eniwetok, Kwajalein</option>
<option value="-11.0"'.($settings2['timeoffset'] == "-12.0" ? " selected" : "").'>(GMT -11:00) Midway Island, Samoa</option>
<option value="-10.0"'.($settings2['timeoffset'] == "-10.0" ? " selected" : "").'>(GMT -10:00) Hawaii</option>
<option value="-9.0"'.($settings2['timeoffset'] == "-9.0" ? " selected" : "").'>(GMT -9:00) Alaska</option>
<option value="-8.0"'.($settings2['timeoffset'] == "-8.0" ? " selected" : "").'>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
<option value="-7.0"'.($settings2['timeoffset'] == "-7.0" ? " selected" : "").'>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
<option value="-6.0"'.($settings2['timeoffset'] == "-6.0" ? " selected" : "").'>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
<option value="-5.0"'.($settings2['timeoffset'] == "-5.0" ? " selected" : "").'>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
<option value="-4.0"'.($settings2['timeoffset'] == "-4.0" ? " selected" : "").'>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
<option value="-3.5"'.($settings2['timeoffset'] == "-3.5" ? " selected" : "").'>(GMT -3:30) Newfoundland</option>
<option value="-3.0"'.($settings2['timeoffset'] == "-3.0" ? " selected" : "").'>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
<option value="-2.0"'.($settings2['timeoffset'] == "-2.0" ? " selected" : "").'>(GMT -2:00) Mid-Atlantic</option>
<option value="-1.0"'.($settings2['timeoffset'] == "-1.0" ? " selected" : "").'>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
<option value="0.0"'.($settings2['timeoffset'] == "0.0" ? " selected" : "").'>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
<option value="1.0"'.($settings2['timeoffset'] == "1.0" ? " selected" : "").'>(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
<option value="2.0"'.($settings2['timeoffset'] == "2.0" ? " selected" : "").'>(GMT +2:00) Kaliningrad, South Africa</option>
<option value="3.0"'.($settings2['timeoffset'] == "3.0" ? " selected" : "").'>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
<option value="3.5"'.($settings2['timeoffset'] == "3.5" ? " selected" : "").'>(GMT +3:30) Tehran</option>
<option value="4.0"'.($settings2['timeoffset'] == "4.0" ? " selected" : "").'>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
<option value="4.5"'.($settings2['timeoffset'] == "4.5" ? " selected" : "").'>(GMT +4:30) Kabul</option>
<option value="5.0"'.($settings2['timeoffset'] == "5.0" ? " selected" : "").'>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
<option value="5.5"'.($settings2['timeoffset'] == "5.5" ? " selected" : "").'>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
<option value="5.75"'.($settings2['timeoffset'] == "5.75" ? " selected" : "").'>(GMT +5:45) Kathmandu</option>
<option value="6.0"'.($settings2['timeoffset'] == "6.0" ? " selected" : "").'>(GMT +6:00) Almaty, Dhaka, Colombo</option>
<option value="7.0"'.($settings2['timeoffset'] == "7.0" ? " selected" : "").'>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
<option value="8.0"'.($settings2['timeoffset'] == "8.0" ? " selected" : "").'>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
<option value="9.0"'.($settings2['timeoffset'] == "9.0" ? " selected" : "").'>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
<option value="9.5"'.($settings2['timeoffset'] == "9.5" ? " selected" : "").'>(GMT +9:30) Adelaide, Darwin</option>
<option value="10.0"'.($settings2['timeoffset'] == "10.0" ? " selected" : "").'>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
<option value="11.0"'.($settings2['timeoffset'] == "11.0" ? " selected" : "").'>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
<option value="12.0"'.($settings2['timeoffset'] == "12.0" ? " selected" : "").'>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>';

$timestamp = time()+($settings2['timeoffset']*3600);

function give_time($var1, $var2) {
global $locale;
	$return = strftime($var1, $var2);
	return ($locale['charset'] == "UTF-8") ? htmlentities($return) : $return; // dirty fix for the German month "März", tell us if you have a better solution!
}

$date_opts = "<option value=''>".$locale['455']."</option>\n";
$date_opts .= "<option value='%m/%d/%Y'>".give_time("%m/%d/%Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%d/%m/%Y'>".give_time("%d/%m/%Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%d-%m-%Y'>".give_time("%d-%m-%Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%d.%m.%Y'>".give_time("%d.%m.%Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%m/%d/%Y %H:%M'>".give_time("%m/%d/%Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%d/%m/%Y %H:%M'>".give_time("%d/%m/%Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%d-%m-%Y %H:%M'>".give_time("%d-%m-%Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%d.%m.%Y %H:%M'>".give_time("%d.%m.%Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%m/%d/%Y %H:%M:%S'>".give_time("%m/%d/%Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%d/%m/%Y %H:%M:%S'>".give_time("%d/%m/%Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%d-%m-%Y %H:%M:%S'>".give_time("%d-%m-%Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%d.%m.%Y %H:%M:%S'>".give_time("%d.%m.%Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%m/%d/%Y %I:%M %p'>".give_time("%m/%d/%Y %I:%M %p", $timestamp)."</option>\n";# %p (pm or am) does not work for me
$date_opts .= "<option value='%d/%m/%Y %I:%M %p'>".give_time("%d/%m/%Y %I:%M %p", $timestamp)."</option>\n";# %p (pm or am) does not work for me
$date_opts .= "<option value='%d-%m-%Y %I:%M %p'>".give_time("%d-%m-%Y %I:%M %p", $timestamp)."</option>\n";# %p (pm or am) does not work for me
$date_opts .= "<option value='%d.%m.%Y %I:%M %p'>".give_time("%d.%m.%Y %I:%M %p", $timestamp)."</option>\n";# %p (pm or am) does not work for me
$date_opts .= "<option value='%B %d %Y'>".give_time("%B %d %Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%d. %B %Y'>".give_time("%d. %B %Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%d %B %Y'>".give_time("%d %B %Y", $timestamp)."</option>\n";
$date_opts .= "<option value='%B %d %Y %H:%M'>".give_time("%B %d %Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%d. %B %Y %H:%M'>".give_time("%d. %B %Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%d %B %Y %H:%M'>".give_time("%d %B %Y %H:%M", $timestamp)."</option>\n";
$date_opts .= "<option value='%B %d %Y %H:%M:%S'>".give_time("%B %d %Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%d. %B %Y %H:%M:%S'>".give_time("%d. %B %Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%d %B %Y %H:%M:%S'>".give_time("%d %B %Y %H:%M:%S", $timestamp)."</option>\n";
$date_opts .= "<option value='%B %d %Y %I:%M %p'>".give_time("%B %d %Y %I:%M %p", $timestamp)."</option>\n";# %p (pm or am) does not work for me
$date_opts .= "<option value='%d. %B %Y %I:%M %p'>".give_time("%d. %B %Y %I:%M %p", $timestamp)."</option>\n";# %p (pm or am) does not work for me
$date_opts .= "<option value='%d %B %Y %I:%M %p'>".give_time("%d %B %Y %I:%M %p", $timestamp)."</option>\n";# %p (pm or am) does not work for me

opentable($locale['400']);
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['451']."</td>\n";
echo "<td width='50%' class='tbl'><select name='shortdatetext' class='textbox' style='width:201px;'>\n".$date_opts."</select><br />\n";
echo "<input type='button' name='setshortdate' value='>>' onclick=\"shortdate.value=shortdatetext.options[shortdatetext.selectedIndex].value;shortdatetext.selectedIndex=0;\" class='button' />\n";
echo "<input type='text' name='shortdate' value='".$settings2['shortdate']."' maxlength='50' class='textbox' style='width:180px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['452']."</td>\n";
echo "<td width='50%' class='tbl'><select name='longdatetext' class='textbox' style='width:201px;'>\n".$date_opts."</select><br />\n";
echo "<input type='button' name='setlongdate' value='>>' onclick=\"longdate.value=longdatetext.options[longdatetext.selectedIndex].value;longdatetext.selectedIndex=0;\" class='button' />\n";
echo "<input type='text' name='longdate' value='".$settings2['longdate']."' maxlength='50' class='textbox' style='width:180px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['453']."</td>\n";
echo "<td width='50%' class='tbl'><select name='forumdatetext' class='textbox' style='width:201px;'>\n".$date_opts."</select><br />\n";
echo "<input type='button' name='setforumdate' value='>>' onclick=\"forumdate.value=forumdatetext.options[forumdatetext.selectedIndex].value;forumdatetext.selectedIndex=0;\" class='button' />\n";
echo "<input type='text' name='forumdate' value='".$settings2['forumdate']."' maxlength='50' class='textbox' style='width:180px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['457']."</td>\n";
echo "<td width='50%' class='tbl'><select name='newsdatetext' class='textbox' style='width:201px;'>\n".$date_opts."</select><br />\n";
echo "<input type='button' name='setnewsate' value='>>' onclick=\"newsdate.value=newsdatetext.options[newsdatetext.selectedIndex].value;newsdatetext.selectedIndex=0;\" class='button' />\n";
echo "<input type='text' name='newsdate' value='".$settings2['newsdate']."' maxlength='50' class='textbox' style='width:180px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['454']."</td>\n";
echo "<td width='50%' class='tbl'><select name='subheaderdatetext' class='textbox' style='width:201px;'>\n".$date_opts."</select><br />\n";
echo "<input type='button' name='setsubheaderdate' value='>>' onclick=\"subheaderdate.value=subheaderdatetext.options[subheaderdatetext.selectedIndex].value;subheaderdatetext.selectedIndex=0;\" class='button' />\n";
echo "<input type='text' name='subheaderdate' value='".$settings2['subheaderdate']."' maxlength='50' class='textbox' style='width:180px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['456']."</td>\n";
echo "<td width='50%' class='tbl'><select name='timeoffset' class='textbox'>\n".$offsetlist."</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require_once TEMPLATES."footer.php";
?>