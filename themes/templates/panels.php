<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: themes/templates/panels.php
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

// Calculate current true url
$script_url = explode("/", $_SERVER['PHP_SELF'].(FUSION_QUERY ? "?".FUSION_QUERY : ""));
$url_count = count($script_url);
$base_url_count = substr_count(BASEDIR, "/") + 1;
$start_page = "";
while ($base_url_count != 0) {
	$current = $url_count - $base_url_count;
	$start_page .= "/".$script_url[$current];
	$base_url_count--;
}

define("START_PAGE", substr(preg_replace("#(&amp;|\?)(s_action=edit&amp;shout_id=)([0-9]+)#s", "", $start_page), 1));

# Pimped ->
$seo_show_panel = false;
if(URL_REWRITE) {
	#fix: for seo url-rewrited startpage
	$fix_url = explode("?", $_SERVER['REQUEST_URI']);
	if(!isset($fix_url['0'])) $fix_url['0'] = $fix_url;
	if(substr($fix_url['0'], 0, 1) == '/') { $fixed_url = substr($fix_url['0'], 1); } else { $fixed_url = $fix_url['0']; }
	#fix 2: example: if news.html is Opening Page, news.php will be Opening Page too; to show the Panel
	$opening_page_in_php = str_replace(".html", ".php", $settings['opening_page']);
	#
	if($settings['opening_page'] == $fixed_url OR $opening_page_in_php == $fixed_url) { $seo_show_panel = true; }
}
# <- Pimped

$p_sql = false; $p_arr = array(1 => false, 2 => false, 3 => false, 4 => false);
if (!defined("ADMIN_PANEL")) {
	if (check_panel_status("left")) {
		$p_sql = "panel_side='1'"; 
	}
	if (check_panel_status("upper")) {
		$p_sql .= ($p_sql ? " OR " : "");
		$p_sql .= (($settings['opening_page'] == START_PAGE) || $seo_show_panel ? "panel_side='2'" : "(panel_side='2' AND panel_display='1')"); // Pimped
	}
	if (check_panel_status("lower")) {
		$p_sql .= ($p_sql ? " OR " : "");
		$p_sql .= (($settings['opening_page'] == START_PAGE) || $seo_show_panel ? "panel_side='3'" : "(panel_side='3' AND panel_display='1')"); // Pimped
	}
	if (check_panel_status("right")) {
		$p_sql .= ($p_sql ? " OR " : "")."panel_side='4'"; 
	}

	$p_sql = ($p_sql ? " AND (".$p_sql.")" : false);
	
if ($p_sql) {
	$p_res = dbquery(
		"SELECT panel_side, panel_type, panel_filename, panel_content FROM ".DB_PANELS." 
		WHERE panel_status='1'".$p_sql." AND ".groupaccess('panel_access')."
		ORDER BY panel_side, panel_order"
	);
	
	if (dbrows($p_res)) {
		$current_side = 0;
		while ($p_data = dbarray($p_res)) {
			if ($current_side == 0) {
				ob_start();
				$current_side = $p_data['panel_side'];
			}
			if ($current_side > 0 && $current_side != $p_data['panel_side']) {
				$p_arr[$current_side] = ob_get_contents();
				ob_end_clean();
				$current_side = $p_data['panel_side'];
				ob_start();
			}
			if ($p_data['panel_type'] == "file") {
				if(file_exists(INFUSIONS.$p_data['panel_filename']."/".$p_data['panel_filename'].".php")) {
					include INFUSIONS.$p_data['panel_filename']."/".$p_data['panel_filename'].".php";
				} else {
					echo "<div class='admin-message'>Error: Panel ".$p_data['panel_filename']." could not been found!</div>";
				}
			} else {
				eval(stripslashes($p_data['panel_content']));
			}
		}
		$p_arr[$current_side] .= ob_get_contents();
		ob_end_clean();
	}
}
} else {
	ob_start();
	require_once ADMIN."navigation.php";
	$p_arr[1] = ob_get_contents();
	ob_end_clean();
}

if (!defined("ADMIN_PANEL")) {
	$p_arr[2] = "<a id='content' name='content'></a>\n".$p_arr[2];
	if (iADMIN && $settings['maintenance']) {
		$p_arr[2] = "<div class='admin-message'>".$locale['global_190']."</div>\n".$p_arr[2];
	}
	if (iSUPERADMIN && file_exists(BASEDIR."_install/setup.php")) { // Pimped
		$p_arr[2] = "<div class='admin-message'>".$locale['global_198']."</div>\n".$p_arr[2];
	}
	if (iSUPERADMIN && file_exists(BASEDIR."_install/update.php")) { // Pimped
		$p_arr[2] = "<div class='admin-message'>".$locale['global_198b']."</div>\n".$p_arr[2]; // Pimped
	}
	if (iADMIN && !$userdata['user_admin_password'] && (iUSER_RIGHTS != "" && iUSER_RIGHTS != "C" && iUSER_RIGHTS != "FMD" && iUSER_RIGHTS != "C.FMD")) {
		$p_arr[2] = "<div id='close-message'><div class='admin-message'>".$locale['global_199']."</div></div>\n".$p_arr[2];
	}
}

define("LEFT", $p_arr[1]);
define("U_CENTER", $p_arr[2]);
define("L_CENTER", $p_arr[3]);
define("RIGHT", $p_arr[4]);

// Set the require div-width class
if(defined("ADMIN_PANEL")) {
	$main_style = "side-left";
} elseif($p_arr[1] && $p_arr[4]) {
	$main_style = "side-both";
} elseif($p_arr[1] && !$p_arr[4]) {
	$main_style = "side-left";
} elseif(!$p_arr[1] && $p_arr[4]) {
	$main_style = "side-right";
} elseif(!$p_arr[1] && !$p_arr[4]) {
	$main_style = "";
}

unset($p_arr);
?>