<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: flash_clock_panel.php
| Author: Kenneth Boldt (kenneth@boldt.me)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/ 
if (!defined("IN_FUSION")) { die("Access Denied"); }

include INFUSIONS."flash_clock_panel/settings.php";

// Check if locale file is available matching the current site locale setting.
if (file_exists(INFUSIONS."flash_clock_panel/locale/".$settings['locale'].".php")) {
	// Load the locale file matching the current site locale setting.
	include INFUSIONS."flash_clock_panel/locale/".$settings['locale'].".php";
} else {
	// Load the infusion's default locale file.
	include INFUSIONS."flash_clock_panel/locale/English.php";
} 
	
Openside($locale['flc_title']);

echo "
<center>
	<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,40,0' width='".$flc_width."' height='".$flc_height."'>
		<param name='movie' value='".INFUSIONS."flash_clock_panel/clocks/".$flc_clock."'>
		<param name='bgcolor' value='".$flc_bgcolor."'>";
if ($flc_transparent == "1") { echo "<param name='wmode' value='transparent'>"; }
echo "		<param name='quality' value='high'>
		<embed src='".INFUSIONS."flash_clock_panel/clocks/".$flc_clock."' quality='high' bgcolor='".$flc_bgcolor."' width='".$flc_width."' height='".$flc_height."'";
if ($flc_transparent == "1") { echo " wmode='transparent' "; }
echo "type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer'>
		</embed>
	</object>
</center>
";




Closeside();
	
	
?>