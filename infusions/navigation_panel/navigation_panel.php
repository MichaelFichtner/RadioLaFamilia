<?php	
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: navigation_panel.php
| Version: Pimped Fusion v0.07.00
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

openside($locale['global_001']);
// Load Navigation Cache
if ($navigation_cache == false) { navigation_cache(); }

if (is_array($navigation_cache) && count($navigation_cache)) {
	for ($i = 0; $i < count($navigation_cache); $i++) {
		if($navigation_cache[$i]['link_position'] <= '2') {
			if ($navigation_cache[$i]['link_name'] != "---" && $navigation_cache[$i]['link_url'] == "---") {
				echo "<div class='side-label'>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</div>\n";
			} else if ($navigation_cache[$i]['link_name'] == "---" && $navigation_cache[$i]['link_url'] == "---") {
				echo "<hr class='side-hr' />\n";
			} else {
				$link_target = ($navigation_cache[$i]['link_window'] == "1" ? " target='_blank'" : "");
				if (strstr($navigation_cache[$i]['link_url'], "http://") || strstr($navigation_cache[$i]['link_url'], "https://")) {
					echo THEME_BULLET." <a href='".$navigation_cache[$i]['link_url']."'".$link_target." class='side'>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</a><br />\n";
				} elseif(URL_REWRITE && $navigation_cache[$i]['link_seo_url'] != '') {
					echo THEME_BULLET." <a href='".BASEDIR.$navigation_cache[$i]['link_seo_url']."'".$link_target." class='side'>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</a><br />\n";
				} else {
					echo THEME_BULLET." <a href='".BASEDIR.$navigation_cache[$i]['link_url']."'".$link_target." class='side'>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</a><br />\n";
				}
			}
		}
	}
} else {
	echo $locale['global_002'];
}
closeside();
?>