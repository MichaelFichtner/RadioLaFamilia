<?php
/*---------------------------------------------------------------------------+
| Pimped-Fusion Content Management System
| Copyright (C) 2009 - 2010
| http://www.pimped-fusion.net
+----------------------------------------------------------------------------+
| Filename: lastvisitors_include.php
| Version: Pimped Fusion v0.08.00
+----------------------------------------------------------------------------+
| Authors: slaughter, Christoph Schreck, Smokeman
+----------------------------------------------------------------------------+
| This program is released as free software under the Affero GPL license.
| You can redistribute it and/or modify it under the terms of this license
| which you can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this copyright header is
| strictly prohibited without written permission from the original author(s).
+---------------------------------------------------------------------------*/
if (!defined("PIMPED_FUSION")) { die("Access Denied"); }

/////////////// CONFIG ///////////////////
$lastvis_showcount = 4; // Number of visitors to show !!!!(max 14)!!!!
$daysshown = 14; // Number of Days, the users are shown
$ava_size = 75; // The width of Avatars size
//////////////////////////////////////////

if ($profile_method == "display") {
	echo "<!-- start lastvisitors  -->";

	$change = false;
	$lastvis_array = explode(".", $user_data['user_lastvisitors']);
	
	if(is_array($lastvis_array) && count($lastvis_array)) {
		foreach($lastvis_array as $key => $vals) {
			$vals_array = explode("|",$vals);
			if((!isset($vals_array[1]) || $vals_array[1] < (time()-$daysshown*3600*24)) || (iMEMBER && $userdata['user_id'] == $vals_array[0])) {
				unset($lastvis_array[$key]); $change = true;
			}
		}
	}
	
	if (iMEMBER && $userdata['user_id'] != $user_data['user_id']) {
		array_unshift($lastvis_array, $userdata['user_id']."|".time());
		$change = true;
	}
	array_splice($lastvis_array,$lastvis_showcount);
	if ($change) { 
		$lastivsquery = dbquery("UPDATE ".DB_USERS." SET user_lastvisitors="._db(implode(".",$lastvis_array))." WHERE user_id='".(int)$user_data['user_id']."'");
	}
	
	$lastvis_show = "";
	
	if (is_array($lastvis_array) && count($lastvis_array)) {
	
		foreach($lastvis_array as $lastvis_data) {
			$lvinfo = explode("|",$lastvis_data);
			$lastvis_uname = false;
			$lastvis_ava = false;

			 $lastvis_ava = dbresult(dbquery("SELECT user_avatar FROM ".DB_USERS." WHERE user_id='".$lvinfo[0]."'"),0);

			if(isnum($lvinfo[0]) && $lvinfo[0] && $lastvis_uname=dbresult(dbquery("SELECT user_name FROM ".DB_USERS." WHERE user_id='".$lvinfo[0]."'"),0)) {
				if (!$lastvis_ava) {
					$lastvis_ava = "noavatar.jpg";
				}
				$lastvis_show .= ($lastvis_show!="" ? " " : "")."<table border='0' cellpadding='5' cellspacing='5' align='left'><tr><td align='center'>".profile_link($lvinfo[0], $lastvis_uname, '0', 'profile-link', $lastvis_uname, '', "<img src='".IMAGES."avatars/".$lastvis_ava."' alt='".$lastvis_uname."' border='0' width='".$ava_size."' height='".$ava_size."' />")."<br />".profile_link($lvinfo[0], $lastvis_uname, '0', 'profile-link', $lastvis_uname, '', $lastvis_uname)."</td></tr></table>\n";
			}
		}
	}

if ($lastvis_show == "") {
	#echo "</tr>\n";
	echo "</table>";
	echo "<div style='margin:5px'></div>\n";
	echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
	echo "<td class='tbl2' colspan='2'><strong>".$locale['uf_lastvis_03']."</strong></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' class='tbl' colspan='2'>".sprintf($locale['uf_lastvis_04'], $daysshown)."</td>\n";
	echo "</tr>\n";
	#echo "</table>";
} else {
	#echo "</tr>\n";
	echo "</table>";
	echo "<div style='margin:5px'></div>\n";
	echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>\n";
	echo "<td class='tbl2' colspan='2'><strong>".$locale['uf_lastvis_03']."</strong></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td class='tbl1' colspan='2' align='center'>".$lastvis_show."</td>";
	echo "</tr>\n";	
}
echo "<!-- end lastvisitors  -->";
}

?>