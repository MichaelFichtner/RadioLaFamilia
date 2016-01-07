<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: user_offset_include.php
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
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
	echo "<tr>\n";
	echo "<td class='tbl'>".$locale['u009'].":</td>\n";
	echo "<td class='tbl'><select name='user_offset' class='textbox'>\n";
	echo '<option value="-12.0"'.($userdata['user_offset'] == "-12.0" ? " selected" : "").'>(GMT -12:00) Eniwetok, Kwajalein</option>
      <option value="-11.0"'.($userdata['user_offset'] == "-12.0" ? " selected" : "").'>(GMT -11:00) Midway Island, Samoa</option>
      <option value="-10.0"'.($userdata['user_offset'] == "-10.0" ? " selected" : "").'>(GMT -10:00) Hawaii</option>
      <option value="-9.0"'.($userdata['user_offset'] == "-9.0" ? " selected" : "").'>(GMT -9:00) Alaska</option>
      <option value="-8.0"'.($userdata['user_offset'] == "-8.0" ? " selected" : "").'>(GMT -8:00) Pacific Time (US &amp; Canada)</option>
      <option value="-7.0"'.($userdata['user_offset'] == "-7.0" ? " selected" : "").'>(GMT -7:00) Mountain Time (US &amp; Canada)</option>
      <option value="-6.0"'.($userdata['user_offset'] == "-6.0" ? " selected" : "").'>(GMT -6:00) Central Time (US &amp; Canada), Mexico City</option>
      <option value="-5.0"'.($userdata['user_offset'] == "-5.0" ? " selected" : "").'>(GMT -5:00) Eastern Time (US &amp; Canada), Bogota, Lima</option>
      <option value="-4.0"'.($userdata['user_offset'] == "-4.0" ? " selected" : "").'>(GMT -4:00) Atlantic Time (Canada), Caracas, La Paz</option>
      <option value="-3.5"'.($userdata['user_offset'] == "-3.5" ? " selected" : "").'>(GMT -3:30) Newfoundland</option>
      <option value="-3.0"'.($userdata['user_offset'] == "-3.0" ? " selected" : "").'>(GMT -3:00) Brazil, Buenos Aires, Georgetown</option>
      <option value="-2.0"'.($userdata['user_offset'] == "-2.0" ? " selected" : "").'>(GMT -2:00) Mid-Atlantic</option>
      <option value="-1.0"'.($userdata['user_offset'] == "-1.0" ? " selected" : "").'>(GMT -1:00 hour) Azores, Cape Verde Islands</option>
      <option value="0.0"'.($userdata['user_offset'] == "0.0" ? " selected" : "").'>(GMT) Western Europe Time, London, Lisbon, Casablanca</option>
      <option value="1.0"'.($userdata['user_offset'] == "1.0" ? " selected" : "").'>(GMT +1:00) Brussels, Copenhagen, Madrid, Paris</option>
      <option value="2.0"'.($userdata['user_offset'] == "2.0" ? " selected" : "").'>(GMT +2:00) Kaliningrad, South Africa</option>
      <option value="3.0"'.($userdata['user_offset'] == "3.0" ? " selected" : "").'>(GMT +3:00) Baghdad, Riyadh, Moscow, St. Petersburg</option>
      <option value="3.5"'.($userdata['user_offset'] == "3.5" ? " selected" : "").'>(GMT +3:30) Tehran</option>
      <option value="4.0"'.($userdata['user_offset'] == "4.0" ? " selected" : "").'>(GMT +4:00) Abu Dhabi, Muscat, Baku, Tbilisi</option>
      <option value="4.5"'.($userdata['user_offset'] == "4.5" ? " selected" : "").'>(GMT +4:30) Kabul</option>
      <option value="5.0"'.($userdata['user_offset'] == "5.0" ? " selected" : "").'>(GMT +5:00) Ekaterinburg, Islamabad, Karachi, Tashkent</option>
      <option value="5.5"'.($userdata['user_offset'] == "5.5" ? " selected" : "").'>(GMT +5:30) Bombay, Calcutta, Madras, New Delhi</option>
      <option value="5.75"'.($userdata['user_offset'] == "5.75" ? " selected" : "").'>(GMT +5:45) Kathmandu</option>
      <option value="6.0"'.($userdata['user_offset'] == "6.0" ? " selected" : "").'>(GMT +6:00) Almaty, Dhaka, Colombo</option>
      <option value="7.0"'.($userdata['user_offset'] == "7.0" ? " selected" : "").'>(GMT +7:00) Bangkok, Hanoi, Jakarta</option>
      <option value="8.0"'.($userdata['user_offset'] == "8.0" ? " selected" : "").'>(GMT +8:00) Beijing, Perth, Singapore, Hong Kong</option>
      <option value="9.0"'.($userdata['user_offset'] == "9.0" ? " selected" : "").'>(GMT +9:00) Tokyo, Seoul, Osaka, Sapporo, Yakutsk</option>
      <option value="9.5"'.($userdata['user_offset'] == "9.5" ? " selected" : "").'>(GMT +9:30) Adelaide, Darwin</option>
      <option value="10.0"'.($userdata['user_offset'] == "10.0" ? " selected" : "").'>(GMT +10:00) Eastern Australia, Guam, Vladivostok</option>
      <option value="11.0"'.($userdata['user_offset'] == "11.0" ? " selected" : "").'>(GMT +11:00) Magadan, Solomon Islands, New Caledonia</option>
      <option value="12.0"'.($userdata['user_offset'] == "12.0" ? " selected" : "").'>(GMT +12:00) Auckland, Wellington, Fiji, Kamchatka</option>
	';
	echo "</select></td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_offset";
	$db_values .= ", '".(isset($_POST['user_offset']) ? (is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0") : "0")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_offset='".(isset($_POST['user_offset']) ? (is_numeric($_POST['user_offset']) ? $_POST['user_offset'] : "0") : "0")."'";
}
?>
