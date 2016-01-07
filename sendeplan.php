<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Sendeplan v2.1 for PHP-Fusion 7
| Filename: sendeplan.php
| Author: Ralf Thieme (Gr@n@dE)
| Homepage: www.granade.eu
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "maincore.php";
require_once THEMES."templates/header.php";

include INFUSIONS."gr_sendeplan/infusion_db.php";
include INFUSIONS."gr_sendeplan/gr_sendeplan_inc.php";
if (file_exists(INFUSIONS."gr_sendeplan/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_sendeplan/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_sendeplan/locale/German/index.php";
}

$settings_result = dbquery("SELECT * FROM ".DB_GR_SENDEPLAN_SETTINGS."");
$sp_settings = dbarray($settings_result);

sp_update();

$result = dbquery("SELECT * FROM ".DB_GR_SENDEPLAN." LIMIT 0, 168");
while($data = dbarray($result)) {
	$info[$data['grs_id']] = dj_info_box($data['grs_user_id'], $data['grs_info'], $data['grs_id'], $data['grs_name']);
}
$wtag = strftime("%u");
add_to_title($locale['global_200'].$locale['grsp102']);
opentable($locale['grsp102']);
echo "<div align='center'>".$locale['grsp118']."</div>\n<br />
<table cellspacing='2' cellpadding='2' class='tbl-border' align='center'>
<tr>
	<td width='40' height='20' class='tbl2' align='center'><b>".$locale['grsp117']."</b></td>
	<td width='80' height='20' class='".($wtag == 1 ? "tbl1" : "tbl2")."' align='center'><b>".$locale['grsp110']."</b><br />".date("d.m.Y", time()+((1-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='".($wtag == 2 ? "tbl1" : "tbl2")."' align='center'><b>".$locale['grsp111']."</b><br />".date("d.m.Y", time()+((2-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='".($wtag == 3 ? "tbl1" : "tbl2")."' align='center'><b>".$locale['grsp112']."</b><br />".date("d.m.Y", time()+((3-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='".($wtag == 4 ? "tbl1" : "tbl2")."' align='center'><b>".$locale['grsp113']."</b><br />".date("d.m.Y", time()+((4-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='".($wtag == 5 ? "tbl1" : "tbl2")."' align='center'><b>".$locale['grsp114']."</b><br />".date("d.m.Y", time()+((5-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='".($wtag == 6 ? "tbl1" : "tbl2")."' align='center'><b>".$locale['grsp115']."</b><br />".date("d.m.Y", time()+((6-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='".($wtag == 7 ? "tbl1" : "tbl2")."' align='center'><b>".$locale['grsp116']."</b><br />".date("d.m.Y", time()+((7-strftime("%u"))*86400))."</td>
</tr>";
$i = 1;
$time_off = 1;
while($i < 163) {
if (sp_offtime($i)) {
	echo "<tr>
	<td align='center' class='tbl2'>".($sp_settings['grss_rhythmus'] == 1 ? $sp_zeit[$i] : $sp_zeit2[$i])."</td>
	<td align='center' class='".($wtag == 1 ? "tbl2" : "tbl1")."'>".$info[$i]."</td>
	<td align='center' class='".($wtag == 2 ? "tbl2" : "tbl1")."'>".$info[$i+1]."</td>
	<td align='center' class='".($wtag == 3 ? "tbl2" : "tbl1")."'>".$info[$i+2]."</td>
	<td align='center' class='".($wtag == 4 ? "tbl2" : "tbl1")."'>".$info[$i+3]."</td>
	<td align='center' class='".($wtag == 5 ? "tbl2" : "tbl1")."'>".$info[$i+4]."</td>
	<td align='center' class='".($wtag == 6 ? "tbl2" : "tbl1")."'>".$info[$i+5]."</td>
	<td align='center' class='".($wtag == 7 ? "tbl2" : "tbl1")."'>".$info[$i+6]."</td>
	</tr>";
} else {
	if ($time_off == 1) {
		echo "<tr><td align='center' class='tbl2' colspan='8'>".$sp_settings['grss_offmsg']."</td></tr>";
	}
	$time_off++;
}
if ($sp_settings['grss_rhythmus'] == 1) {
$i2 = $i+7;
if (sp_offtime($i2)) {
	echo "<tr>
	<td align='center' class='tbl2'>".$sp_zeit[$i2]."</td>
	<td align='center' class='".($wtag == 1 ? "tbl2" : "tbl1")."'>".$info[$i+7]."</td>
	<td align='center' class='".($wtag == 2 ? "tbl2" : "tbl1")."'>".$info[$i+8]."</td>
	<td align='center' class='".($wtag == 3 ? "tbl2" : "tbl1")."'>".$info[$i+9]."</td>
	<td align='center' class='".($wtag == 4 ? "tbl2" : "tbl1")."'>".$info[$i+10]."</td>
	<td align='center' class='".($wtag == 5 ? "tbl2" : "tbl1")."'>".$info[$i+11]."</td>
	<td align='center' class='".($wtag == 6 ? "tbl2" : "tbl1")."'>".$info[$i+12]."</td>
	<td align='center' class='".($wtag == 7 ? "tbl2" : "tbl1")."'>".$info[$i+13]."</td>
	</tr>";
} else {
	if ($time_off == 1) {
	echo "<tr>\n<td align='center' class='tbl2' colspan='8'>".$sp_settings['grss_offmsg']."</td>\n</tr>\n";
	}
	$time_off++;
}
}
$i = $i+14;
}
echo "</table>\n<br />";
if ($sp_settings['grss_preview'] != 0 || (sp_group($sp_settings['grss_sgroup']) || sp_group($sp_settings['grss_ggroup'])) || sp_group($sp_settings['grss_agroup']) || iSUPERADMIN) {
$result = dbquery("SELECT * FROM ".DB_GR_SENDEPLAN." LIMIT 168, 336");
while($data = dbarray($result)) {
	$info[$data['grs_id']] = dj_info_box($data['grs_user_id'], $data['grs_info'], $data['grs_id'], $data['grs_name']);
}
closetable();
opentable($locale['grsp103']);
echo "<div align='center'>".$locale['grsp119']."</div>\n<br />
<table cellspacing='2' cellpadding='2' class='tbl-border' align='center'>
<tr>
	<td width='40' height='20' class='tbl2' align='center'><b>".$locale['grsp117']."</b></td>
	<td width='80' height='20' class='tbl2' align='center'><b>".$locale['grsp110']."</b><br />".date("d.m.Y", time()+((8-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='tbl2' align='center'><b>".$locale['grsp111']."</b><br />".date("d.m.Y", time()+((9-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='tbl2' align='center'><b>".$locale['grsp112']."</b><br />".date("d.m.Y", time()+((10-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='tbl2' align='center'><b>".$locale['grsp113']."</b><br />".date("d.m.Y", time()+((11-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='tbl2' align='center'><b>".$locale['grsp114']."</b><br />".date("d.m.Y", time()+((12-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='tbl2' align='center'><b>".$locale['grsp115']."</b><br />".date("d.m.Y", time()+((13-strftime("%u"))*86400))."</td>
	<td width='80' height='20' class='tbl2' align='center'><b>".$locale['grsp116']."</b><br />".date("d.m.Y", time()+((14-strftime("%u"))*86400))."</td>
</tr>";
$i = 169;
$i2 = 1;
$time_off2 = 1;
while($i < 335) {
if (sp_offtime($i)) {
	echo "<tr>
	<td align='center' class='tbl2'>".($sp_settings['grss_rhythmus'] == 1 ? $sp_zeit[$i2] : $sp_zeit2[$i2])."</td>
	<td align='center' class='tbl1'>".$info[$i]."</td>
	<td align='center' class='tbl1'>".$info[$i+1]."</td>
	<td align='center' class='tbl1'>".$info[$i+2]."</td>
	<td align='center' class='tbl1'>".$info[$i+3]."</td>
	<td align='center' class='tbl1'>".$info[$i+4]."</td>
	<td align='center' class='tbl1'>".$info[$i+5]."</td>
	<td align='center' class='tbl1'>".$info[$i+6]."</td>
	</tr>";
} else {
	if ($time_off2 == 1) {
	echo "<tr>\n<td align='center' class='tbl2' colspan='8'>".$sp_settings['grss_offmsg']."</td>\n</tr>\n";
	}
	$time_off2++;
}
if ($sp_settings['grss_rhythmus'] == 1) {
$i3 = $i2+7;
if (sp_offtime($i3)) {
	echo "<tr>
	<td align='center' class='tbl2'>".$sp_zeit[$i3]."</td>
	<td align='center' class='tbl1'>".$info[$i+7]."</td>
	<td align='center' class='tbl1'>".$info[$i+8]."</td>
	<td align='center' class='tbl1'>".$info[$i+9]."</td>
	<td align='center' class='tbl1'>".$info[$i+10]."</td>
	<td align='center' class='tbl1'>".$info[$i+11]."</td>
	<td align='center' class='tbl1'>".$info[$i+12]."</td>
	<td align='center' class='tbl1'>".$info[$i+13]."</td>
	</tr>";
} else {
	if ($time_off2 == 1) {
	echo "<tr>\n<td align='center' class='tbl2' colspan='8'>".$sp_settings['grss_offmsg']."</td>\n</tr>\n";
	}
	$time_off2++;
}
}
$i = $i+14;
$i2 = $i2+14;
}
echo "</table>\n<br />";
}

echo "<div align='right'><a href='http://www.granade.eu/scripte/sendeplan.html' target='_blank'>Sendeplan &copy;</a></div>";
closetable();

require_once THEMES."templates/footer.php";
?>