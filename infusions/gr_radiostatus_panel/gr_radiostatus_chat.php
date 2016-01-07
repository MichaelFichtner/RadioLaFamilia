<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Radiostatus v1.0 for PHP-Fusion 7
| Filename: gr_radiostatus_admin.php
| Author: Ralf Thieme
| Webseite: www.granade.eu
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once dirname(__FILE__)."/../../maincore.php";

if (!defined("RADIOSTATUS_SELF")) {
	define("RADIOSTATUS_SELF", dirname($_SERVER['PHP_SELF'])."/");
}
if (!defined("RADIOSTATUS")) {
	define("RADIOSTATUS", INFUSIONS."gr_radiostatus_panel/");
}

include RADIOSTATUS."infusion_db.php";
if (file_exists(RADIOSTATUS."locale/".LOCALESET."index.php")) {
	include RADIOSTATUS."locale/".LOCALESET."index.php";
} else {
	include RADIOSTATUS."locale/German/index.php";
}
include RADIOSTATUS."gr_radiostatus_class.php";

// mein teil
include RADIOSTATUS."sc_trans_port.php";
// end

header("Expires: Sat, 05 Nov 2005 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

function rs_player($id, $ip, $port, $ps=0, $tele=0) {
	$ausgabe = "<a href='http://".$ip.":".$port."/listen.pls'><img src='".RADIOSTATUS_SELF."images/winamp.gif' alt='Winamp' border='0' width='17' height='17' /></a> &nbsp&nbsp";
	$ausgabe .= "<img src='".RADIOSTATUS_SELF."images/real.gif' alt='Real' border='0' width='17' height='17' onclick='rsreal(".$id.")' /> &nbsp&nbsp";
	$ausgabe .= "<img src='".RADIOSTATUS_SELF."images/wmp.gif' alt='WMP' border='0' width='17' height='17' onclick='rswmp(".$id.")' /> &nbsp&nbsp";
	$ausgabe .= "<img src='".RADIOSTATUS_SELF."images/qt.gif' alt='Quicktime' border='0' width='17' height='17' onclick='rsqt(".$id.")' /> &nbsp&nbsp";
	if ($ps) {
		$ausgabe .= "<a href='psradio://%7CChannelId%7C".$ps."%29;%20' target='_blank'><img src='".RADIOSTATUS_SELF."images/ps.gif' border='0' width='17' height='17' alt='ps' /></a> ";
	}
	if ($tele) {
		$ausgabe .= "<img src='".RADIOSTATUS_SELF."images/tele.gif' border='0' width='17' height='17' alt='ps' onclick='msg(\"".$tele."\")' /> ";
	}
	return $ausgabe;
}

function rs_panel($info) {
	global $userdata, $locale, $settings, $match;
	$server_data="";
	$server_data=unserialize($info);
	$ausgabe = ""; $dj_anzeige = ""; $server = "";
	$server = new SHOUTcast();
	if ($server->GetStatus($server_data['ip'], $server_data['port'], $server_data['pw'])) {
		if ($server->GetStreamStatus()) {
			// if ($server_data['usertyp'] == 1) {
				// $server_name = $server->GetAIM();
			// } elseif ($server_data['usertyp'] == 2) {
				// $server_name = $server->GetICQ();
			// } elseif ($server_data['usertyp'] == 3) {
				// $server_name = $server->GetIRC();
			// } elseif ($server_data['usertyp'] == 4) {
				// $server_name = $server->GetServerTitle();
			// } else {
				// $server_name = $server->GetAIM();
			// }
            
            // if ($server_name == "N/A"){
                // $server_name_rlf = $match;
            // }
            
			$server_name_rlf = $match;
			
			$result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_name='".stripinput($server_name_rlf)."'");
			if (dbrows($result)) {
				$data = dbarray($result);
				$DJ_NAME="<a href='".RADIOSTATUS_SELF."../../profile.php?lookup=".$data['user_id']."'>".trimlink($server_name_rlf, 20)."</a>";
				if ($data['user_avatar'] && file_exists(IMAGES."avatars/".$data['user_avatar'])) {
					$DJ_PIC = RADIOSTATUS_SELF.IMAGES."avatars/".$data['user_avatar'];
				} else {
					$DJ_PIC = RADIOSTATUS_SELF."images/nopic.gif";
				}
				// if (array_key_exists("user_aim", $data) && $data['user_aim']) {
					// $MSG_AIM="<img src='".RADIOSTATUS_SELF."images/aim.gif' border='0' width='16' height='16' alt='AIM' onclick='msg(\"".$data['user_aim']."\")' />";
				// } else {
					// $MSG_AIM="";
				// }
				// if (array_key_exists("user_icq", $data) && $data['user_icq']) {
					// $MSG_ICQ="<img src='".RADIOSTATUS_SELF."images/icq.gif' border='0' width='16' height='16' alt='ICQ' onclick='msg(\"".$data['user_icq']."\")' />";
				// } else {
					// $MSG_ICQ="";
				// }
				// if (array_key_exists("user_msn", $data) && $data['user_msn']) {
					// $MSG_MSN="<img src='".RADIOSTATUS_SELF."images/msn.gif' border='0' width='16' height='16' alt='MSN' onclick='msg(\"".$data['user_msn']."\")' />";
				// } else {
					// $MSG_MSN="";
				// }
				// if (array_key_exists("user_yahoo", $data) && $data['user_yahoo']) {
					// $MSG_YAHOO="<img src='".RADIOSTATUS_SELF."images/yahoo.gif' border='0' width='16' height='16' alt='Yahoo' onclick='msg(\"".$data['user_yahoo']."\")' />";
				// } else {
					// $MSG_YAHOO="";
				// }
				// if($server_data['gb']) {
					// $MSG_GWB="<img src='".RADIOSTATUS_SELF."images/popup.gif' border='0' alt='GB' onclick='gb(\"".$server_data['id']."\")' />";
				// } else {
					// $MSG_GWB="";
				// }
				$DJ_STYLE=1;
			} else {
				$DJ_NAME=$locale['grrs_37'];
				$DJ_PIC = RADIOSTATUS_SELF."images/autodj2.png";
				$MSG_GWB=""; $MSG_AIM=""; $MSG_ICQ=""; $MSG_MSN=""; $MSG_YAHOO=""; $DJ_STYLE=0;
			}
			$ausgabe .= "<div align='center'>\n<hr class='side-hr' />\n<span style='font-weight: bold;color: #ff0000;font-size: 16px;'>".$server_data['name']."</span>\n<hr class='side-hr' />\n</div>\n";
			$ausgabe .= "<table align='center' border='0' cellpadding='1' cellspacing='1' width='150px'>\n";
			$ausgabe .= "<tr>\n";
			if ($DJ_STYLE==1) {
				$ausgabe .= "<td colspan='2' align='center' height='25px' valign='middle'>".$DJ_NAME."</td>\n";
				$ausgabe .= "</tr>\n";
				$ausgabe .= "<tr>\n";
				$ausgabe .= "<td width='50px' align='center'><img src='".$DJ_PIC."' border='0' height='100' alt='".$server_name_rlf."' /></td>\n";
				$ausgabe .= "</tr>";
				// $ausgabe .= "</tr><tr>\n";
				// $ausgabe .= "<td valign='middle' align='center' height='60px'>".(($MSG_GWB != "" ? $MSG_GWB : "")."<br />".($MSG_AIM != "" ? $MSG_AIM : "")."&nbsp&nbsp&nbsp&nbsp".($MSG_ICQ != "" ? $MSG_ICQ : "")."&nbsp&nbsp&nbsp&nbsp".($MSG_MSN != "" ? $MSG_MSN : "")."&nbsp&nbsp&nbsp&nbsp".($MSG_YAHOO != "" ? $MSG_YAHOO : ""))."</td>\n";
				// $ausgabe .= "</tr>\n";
			} else {
				$ausgabe .= "<tr><td height='25px' valign='middle' align='center'><span style='font-weight: bold;'>".$DJ_NAME."</span></td>\n";
				$ausgabe .= "<tr><td width='50px' align='center'><img src='".$DJ_PIC."' border='0' height='100' alt='".$server_name_rlf."' /></td></tr>\n";
				//$ausgabe .= "<td>&nbsp</td>\n";
				$ausgabe .= "</tr>\n";	
			}
			$ausgabe .= "<tr>\n";
			if (isset($_SERVER['HTTP_USER_AGENT']) && !preg_match('/firefox/i', $_SERVER['HTTP_USER_AGENT'])) {
				$ausgabe .= "<td colspan='2'><marquee name='marquee' behavior='scroll' scrollamount='2' scrolldelay='80' width='100%' onmouseover='this.stop()' onmouseout='this.start()'>".$server->GetCurrentSongTitle()."</marquee></td>\n";
			} else {
				$ausgabe .= "<td colspan='2'><marquee scrollamount='2' scrolldelay='80'>".$server->GetCurrentSongTitle()."</marquee></td>\n";
			}
			$ausgabe .= "</tr>\n";
			
			$ausgabe .= "<tr>\n";
			$ausgabe .= "<td colspan='2' align='center' height='40px' valign='middle'>".rs_player($server_data['id'], $server_data['ip'], $server_data['port'], $server_data['ps'], $server_data['tele'])."</td>\n";
			$ausgabe .= "</tr>\n";
			$ausgabe .= "</table>\n";
		} else {
			$ausgabe .= "<div align='center'>\n<hr class='side-hr' />\n".$server_data['name']."\n<hr class='side-hr' />\n";
			$ausgabe .= "<img src='".RADIOSTATUS_SELF."images/offline.gif' border='0' alt='Offline' />\n</div>\n";
		}
	} else {
		$ausgabe .= "<div align='center'>\n<hr class='side-hr' />\n".$server_data['name']."\n<hr class='side-hr' />\n";
		$ausgabe .= "<img src='".RADIOSTATUS_SELF."images/offline.gif' border='0' alt='Offline' />";
		if (iSUPERADMIN) {
			$ausgabe .= "<br />".$server->GetError();
		}
		$ausgabe .= "</div>\n";
	}
	return $ausgabe;
}

$result = dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS." WHERE ".groupaccess("rs_access")." AND rs_status='1' ORDER BY rs_order");
if (dbrows($result)) {
	while ($data = dbarray($result)) {
		$data_info = array();
		$data_info['id'] = $data['rs_id'];
		$data_info['name'] = $data['rs_name'];
		$data_info['ip'] = $data['rs_ip'];
		$data_info['port'] = $data['rs_port'];
		$data_info['pw'] = $data['rs_pw'];
		$data_info['gb'] = $data['rs_gb'];
		$data_info['ps'] = $data['rs_ps'];
		$data_info['tele'] = $data['rs_tele'];
		$data_info['servertyp'] = $data['rs_servertyp'];
		$data_info['usertyp'] = $data['rs_usertyp'];
		$data_info['status'] = $data['rs_status'];
		$data_info['gaccess'] = $data['rs_gaccess'];
		$data_info['access'] = $data['rs_access'];
		echo rs_panel(serialize($data_info));
	}
	echo "<script type='text/javascript'>
	document.getElementsByTagName('marquee').start;
	</script>";
} else {
	echo "<div align='center'>".$locale['grrs_35']."</div>";
}
mysql_close();
?>