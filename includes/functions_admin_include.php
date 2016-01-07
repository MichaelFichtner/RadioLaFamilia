<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: functions_admin_include.php
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
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

// Sets the value of a setting in the settings table
function set_mainsetting($setting_name, $setting_value) {
	$result = dbquery("UPDATE ".DB_SETTINGS." SET settings_value="._db($setting_value)." WHERE settings_name="._db($setting_name));
	return ($result ? true : false);
}

// Site Admin Help
function info_helper($page, $scrollbar = false) {
global $locale;
$scrollbar = $scrollbar == true ? "yes" : "no";
return "<a href=\"#\" onClick=\"window.open('help.php?page=".$page."', 'NewWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=".$scrollbar.",resizable=no,copyhistory=no,width=300,height=400')\">".get_image("info", "Help", "border:0;", "Some Information")."</a>";

}

// Colour Function
function color_mapper($field, $value, $enable = true) { // Pimped

	$cvalue[] = "00";
	$cvalue[] = "33";
	$cvalue[] = "66";
	$cvalue[] = "99";
	$cvalue[] = "CC";
	$cvalue[] = "FF";
	$select = "";
	$select = "<select name='".$field."' class='textbox' onchange=\"document.getElementById('preview_".$field."').style.background = '#' + this.options[this.selectedIndex].value;\" ".(!$enable  ? "disabled='disabled'" : "").">\n";
	for ($ca=0; $ca<count($cvalue); $ca++) {
		for ($cb=0; $cb<count($cvalue); $cb++) {
			for ($cc=0; $cc<count($cvalue); $cc++) {
				$hcolor = $cvalue[$ca].$cvalue[$cb].$cvalue[$cc];
				$select .= "<option value='".$hcolor."'".($value==$hcolor?" selected='selected' ":" ")."style='background-color:#".$hcolor.";'>#".$hcolor."</option>\n";
			}
		}
	}
	$select .= "</select>\n";
	return $select;
}

?>