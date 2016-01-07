<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: last_seen_users_panel.php
| Version: Pimped Fusion v0.05.00
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

if (file_exists(INFUSIONS."last_seen_users_panel/locale/".$settings['locale'].".php")) {
	include INFUSIONS."last_seen_users_panel/locale/".$settings['locale'].".php";
} else {
	include INFUSIONS."last_seen_users_panel/locale/English.php";
}

add_to_head("<script type='text/javascript'>
<!--
function show_more_users() {
	var smu = document.getElementById('show_more_users');
	var smutxt = document.getElementById('show_more_users_text');
	if (smu.style.display == 'none') {
	smu.style.display = 'block';
	smutxt.innerHTML = '".$locale['lsup008']."';
	} else {
	smu.style.display = 'none';
	smutxt.innerHTML = '".$locale['lsup007']."';
	}
}
//-->
</script>");

openside($locale['lsup000']);
$result = dbquery("SELECT user_id, user_name, user_status, user_lastvisit FROM ".DB_USERS." WHERE user_lastvisit>'0' AND user_status='0' ORDER BY user_lastvisit DESC LIMIT 0,".($settings['last_seen_users_show'] + $settings['last_seen_users_show_more']));
echo "<table cellpadding='0' cellspacing='0' width='100%'>\n";
if (dbrows($result) != 0) {
	$i = 0;
	while ($data = dbarray($result)) {
		if ($i == $settings['last_seen_users_show']) {
			echo "</table><br />";
			echo "<div align='center'>";
			echo "<img src='".INFUSIONS."last_seen_users_panel/images/display_more.gif' alt='' />&nbsp;<a href=\"javascript:void(0)\" onclick=\"show_more_users();\"><span id='show_more_users_text'>".$locale['lsup007']."</span></a>";
			echo "</div>";
			echo "<div id='show_more_users' style='display: none;'><br />";
			echo "<table cellpadding='0' cellspacing='0' width='100%'>";
		}
		$lastseen = time() - $data['user_lastvisit'];
		$iW = sprintf("%2d", floor($lastseen / 604800));
		$iD = sprintf("%2d", floor($lastseen / (60 * 60 * 24)));
		$iH = sprintf("%02d", floor((($lastseen % 604800) % 86400) / 3600));
		$iM = sprintf("%02d", floor(((($lastseen % 604800) % 86400) % 3600) / 60));
		$iS = sprintf("%02d", floor((((($lastseen % 604800) % 86400) % 3600) % 60)));
		if ($lastseen < 60){
			if($settings['last_seen_users_colors'] && $settings['last_seen_users_color1'] != '') {
				$lastseen = "<span style='color:#".$settings['last_seen_users_color1']."'>".$locale['lsup001']."</span>";
			} else {
				$lastseen = $locale['lsup001'];
			}
		} elseif ($lastseen < 360){
			if($settings['last_seen_users_colors'] && $settings['last_seen_users_color2'] != '') {
				$lastseen = "<span style='color:#".$settings['last_seen_users_color2']."'>".$locale['lsup002']."</span>";
			} else {
				$lastseen = $locale['lsup002'];
			}
		} elseif ($iW > 0){
			if ($iW == 1) {
				$text = $locale['lsup003'];
			} else {
				$text = $locale['lsup004'];
			}
			$lastseen = $iW." ".$text;
		} elseif ($iD > 0){
			if ($iD == 1) {
				$text = $locale['lsup005'];
			} else {
				$text = $locale['lsup006'];
			}
			$lastseen = $iD." ".$text;
		} else {
			$lastseen = $iH.":".$iM.":".$iS;
		}
		echo "<tr>\n<td class='side-small' align='left'>".THEME_BULLET."\n";
		echo profile_link($data['user_id'], $data['user_name'], $data['user_status'], "side", $data['user_name'])."</td><td class='side-small' align='right'>".$lastseen."</td>\n</tr>\n";
$i ++;
	}
}
echo "</table>";
if($i > $settings['last_seen_users_show']) {
	echo "</div>\n";
}
closeside();
?>