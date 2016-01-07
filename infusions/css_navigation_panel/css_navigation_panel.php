<?php	
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: css_navigation_panel.php
| Version: Pimped Fusion v0.06.00
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

$list_open = false;

openside($locale['global_001']);
// Load Navigation Cache
if ($navigation_cache == false) { navigation_cache(); }

if (is_array($navigation_cache) && count($navigation_cache)) {
	echo "<div id='navigation'>\n";
	for ($i = 0; $i < count($navigation_cache); $i++) {
		if($navigation_cache[$i]['link_position'] <= '2') {
			if ($navigation_cache[$i]['link_name'] != "---" && $navigation_cache[$i]['link_url'] == "---") {
				if ($list_open) { echo "</ul>\n"; $list_open = false; }
				echo "<h2>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</h2>\n";
			} elseif ($navigation_cache[$i]['link_name'] == "---" && $navigation_cache[$i]['link_url'] == "---") {
				if ($list_open) { echo "</ul>\n"; $list_open = false; }
				echo "<hr class='side-hr' />\n";
			} else {
				if (!$list_open) { echo "<ul>\n"; $list_open = true; }
				$link_target = ($navigation_cache[$i]['link_window'] == "1" ? " target='_blank'" : "");
				if (strstr($navigation_cache[$i]['link_url'], "http://") || strstr($navigation_cache[$i]['link_url'], "https://")) {
					echo "<li><a href='".$navigation_cache[$i]['link_url']."'".$link_target." class='side'>".THEME_BULLET." <span>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</span></a></li>\n";
				} elseif(URL_REWRITE && $navigation_cache[$i]['link_seo_url'] != '') {
					echo "<li><a href='".BASEDIR.$navigation_cache[$i]['link_seo_url']."'".$link_target." class='side'>".THEME_BULLET." <span>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</span></a></li>\n";
				} else {
					echo "<li><a href='".BASEDIR.$navigation_cache[$i]['link_url']."'".$link_target." class='side'>".THEME_BULLET." <span>".parseubb($navigation_cache[$i]['link_name'], "b|i|u|color")."</span></a></li>\n";
				}
			}
		}
	}
	if ($list_open) { echo "</ul>\n"; }
	echo "</div>\n";
} else {
	echo $locale['global_002'];
}
closeside();
?>