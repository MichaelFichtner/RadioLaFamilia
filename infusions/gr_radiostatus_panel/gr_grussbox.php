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
require_once "../../maincore.php";
require_once THEME."theme.php";

include INFUSIONS."gr_radiostatus_panel/infusion_db.php";
if (file_exists(INFUSIONS."gr_radiostatus_panel/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_radiostatus_panel/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_radiostatus_panel/locale/German/index.php";
}

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' media='screen' />\n";
if (file_exists(IMAGES."favicon.ico")) { echo "<link rel='shortcut icon' href='".IMAGES."favicon.ico' type='image/x-icon' />\n"; }
echo "<script type='text/javascript' src='".INCLUDES."jscript.js'></script>\n";
echo "<script type='text/javascript' src='".INCLUDES."jquery.js'></script>\n";
if (function_exists("get_head_tags")) { echo get_head_tags(); }
if (isset($_GET['admin'])) {
	echo "<meta http-equiv='refresh' content='30; URL=".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : '')."' >";
}
echo "</head>\n<body>\n";
if (isset($_GET['id']) && isnum($_GET['id'])) {
	$result = dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS." WHERE rs_id='".$_GET['id']."'");
	if (dbrows($result)) {
		$data = dbarray($result);
		if (isset($_GET['admin']) && checkgroup($data['rs_gaccess'])) {
			if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart']=0; }
			function wunsch_sound ($file,$start,$loop){
				echo "<object type='application/x-shockwave-flash' data='".INFUSIONS."gr_radiostatus_panel/standard.swf?src=".INFUSIONS."gr_radiostatus_panel/".$file.".mp3&autostart=".$start."&loop=".$loop."' width='0' height='0'></object>";
			}
			if (isset($_GET['delete']) && isnum($_GET['delete'])) {
				$result = dbquery("DELETE FROM ".DB_GR_RADIOSTATUS_GRUSSBOX." WHERE rsgb_id='".$_GET['delete']."'");
				redirect(FUSION_SELF."?id=".$_GET['id']."&admin");
			}
			opentable($data['rs_name']." - ".$locale['grrs_06']);
			if(function_exists('fsockopen')) {
				include INFUSIONS."gr_radiostatus_panel/gr_radiostatus_class.php";
				// Live....
				$server = ""; $server = new SHOUTcast();
				if ($server->GetStatus($data['rs_ip'], $data['rs_port'], $data['rs_pw'])) {
					if ($server->GetStreamStatus()) {
						echo "<table cellpadding='0' cellspacing='0' width='100%' align='center' class='tbl-border'>\n<tr>\n";
						echo "<td class='tbl1' width='50%'>Aktuelle Zuh&ouml;rer:</td>\n";
						echo "<td class='tbl1' width='50%'>".$server->GetCurrentListeners()."/".$server->GetMaxListeners()."</td>\n";
						echo "</tr>\n</table>\n";
					}
				}
				// .... Live
			}
			$result = dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS_GRUSSBOX." WHERE rsgb_stream='".$_GET['id']."' ORDER BY rsgb_time LIMIT ".$_GET['rowstart'].",5");
			if (dbrows($result)) {
				echo "<table cellpadding='1' cellspacing='1' width='100%' align='center' class='tbl-border'>\n";
				echo "<tr>\n";
				echo "<td class='tbl1' width='100px'>".$locale['grrs_01']."</td>\n";
				echo "<td class='tbl1' width='100px'>".$locale['grrs_51']."</td>\n";
				echo "<td class='tbl1'>".$locale['grrs_52']."</td>\n";
				echo "<td class='tbl2' width='50px'></td>\n";
				echo "</tr>\n";
				while ($data = dbarray($result)) {
					echo "<tr>\n";
					echo "<td class='tbl1'>".$data['rsgb_username']."<br />aus ".$data['rsgb_ort']."<br />".$data['rsgb_userip']."<br />".showdate("%d.%m. / %H:%M", $data['rsgb_time'])."</td>\n";
					echo "<td class='tbl1'>".$data['rsgb_interpreter']."<br />".$data['rsgb_title']."</td>\n";
					echo "<td class='tbl1' valign='top'>".$data['rsgb_gruss']."</td>\n";
					echo "<td class='tbl2'><a href='".FUSION_SELF."?id=".$_GET['id']."&amp;admin&amp;delete=".$data['rsgb_id']."'>".$locale['grrs_53']."</a></td>\n";
					echo "</tr>\n";
				}
				echo "</table>\n";
				$rows = dbrows(dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS_GRUSSBOX." WHERE rsgb_stream='".$_GET['id']."'"));
				if ($rows > 5) echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'],5,$rows,3, FUSION_SELF."?id=".$_GET['id']."&amp;admin&amp;")."\n</div>\n";
				$rows2 = dbrows(dbquery("SELECT * FROM ".DB_GR_RADIOSTATUS_GRUSSBOX." WHERE rsgb_stream='".$_GET['id']."' AND rsgb_status='1'"));
				if ($rows2 > 0) {
					echo wunsch_sound('wunschbox','yes','no');
					$result = dbquery("UPDATE ".DB_GR_RADIOSTATUS_GRUSSBOX." SET rsgb_status='0' WHERE rsgb_stream='".$_GET['id']."' AND rsgb_status='1'");
				}
			} else {
				echo "<div align='center'>".$locale['grrs_50']."</div>";
			}
		} elseif ($data['rs_gb'] && checkgroup($data['rs_access'])) {
			if (isset($_POST['save'])) {
				include_once INCLUDES."securimage/securimage.php";
				$sicherheit = 1;
				if (iGUEST && $settings['display_validation'] == "1") {
					$securimage = new Securimage();
					if (!isset($_POST['captcha_code']) || $securimage->check($_POST['captcha_code']) == false) {
						$sicherheit = 0;
					}
				}
				$name = (isset($_POST['name']) ? stripinput($_POST['name']) : 0);
				$ort = (isset($_POST['ort']) ? stripinput($_POST['ort']) : 0);
				$interpreter = (isset($_POST['interpreter']) ? stripinput($_POST['interpreter']) : "");
				$title = (isset($_POST['title']) ? stripinput($_POST['title']) : "");
				if (isset($_POST['gruss'])) {
					$gruss = str_replace("\n", " ", $_POST['gruss']);
					$gruss = preg_replace("/^(.{255}).*$/", "$1", $gruss);
					$gruss = preg_replace("/([^\s]{25})/", "$1\n", $gruss);
					$gruss = trim(stripinput(censorwords($gruss)));
					$gruss = str_replace("\n", "<br />", $gruss);
				} else {
					$gruss = 0;
				}
				if ($sicherheit && $name && $ort && $gruss) {
					$result = dbquery("INSERT INTO ".DB_GR_RADIOSTATUS_GRUSSBOX." (rsgb_userip, rsgb_username, rsgb_ort, rsgb_title, rsgb_interpreter, rsgb_gruss, rsgb_time, rsgb_status, rsgb_stream) VALUES('".USER_IP."', '".$name."', '".$ort."', '".$title."', '".$interpreter."', '".$gruss."', '".time()."', '1', '".$_GET['id']."')"); 
					redirect(FUSION_SELF."?id=".$_GET['id']."&amp;error=0");
				} else {
					redirect(FUSION_SELF."?id=".$_GET['id']."&amp;error=1");
				}
			} else {
				opentable($data['rs_name'].$locale['grrs_41']);
				if (checkgroup($data['rs_gaccess'])) {
					echo "<a href='".FUSION_SELF."?id=".$_GET['id']."&amp;admin'>Admin</a><br />";
				}
				if (isset($_GET['error']) && $_GET['error'] == 0) {
					echo "<div class='admin-message'>".$locale['grrs_42']."</div>\n";
				} elseif (isset($_GET['error']) && $_GET['error'] == 1) {
					echo "<div class='admin-message'>".$locale['grrs_43']."</div>\n";
				} else {
					echo "<div class='admin-message'>".$locale['grrs_44']."</div>\n";
				}
				echo "<form method='post' action='".FUSION_SELF.(FUSION_QUERY ? "?".FUSION_QUERY : "")."'>\n";
				echo "<table cellspacing='1' cellpadding='1' width='500' class='tbl-border' align='center'>\n";
				echo "<tr>\n";
				echo "<td class='tbl1' width='50'>".$locale['grrs_01'].":<span style='color: rgb(255, 0, 0);'>*</span></td>\n";
				echo "<td class='tbl2' width='200'><input type='text' name='name' class='textbox' style='width:195px'".(iMEMBER ? " value='".$userdata['user_name']."' readonly='readonly'" : "")." maxlength='100' /></td>\n";
				echo "<td class='tbl1' width='50'>".$locale['grrs_45'].":<span style='color: rgb(255, 0, 0);'>*</span></td>\n";
				echo "<td class='tbl2' width='200'><input type='text' name='ort' class='textbox' style='width:195px'".(iMEMBER && array_key_exists("user_location", $userdata) ? " value='".$userdata['user_location']."'" : "")." maxlength='100' /></td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td class='tbl1'>".$locale['grrs_46'].":</td>\n";
				echo "<td class='tbl2'><input type='text' name='interpreter' class='textbox' style='width:195px' maxlength='100' /></td>\n";
				echo "<td class='tbl1'>".$locale['grrs_47'].":</td>\n";
				echo "<td class='tbl2'><input type='text' name='title' class='textbox' style='width:195px' maxlength='100' /></td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				echo "<td class='tbl1' width='50' valign='top'>".$locale['grrs_48'].":<span style='color: rgb(255, 0, 0);'>*</span></td>\n";
				echo "<td class='tbl2' colspan='3' width='450'><textarea name='gruss' rows='2' class='textbox' style='width:470px'></textarea></td>\n";
				echo "</tr>\n";
				echo "<tr>\n";
				if (iGUEST) {
					echo "<td class='tbl1' width='50'><img id='captcha' src='".INCLUDES."securimage/securimage_show.php' alt='' align='left' />\n";
				  echo "<a href='".INCLUDES."securimage/securimage_play.php'><img src='".INCLUDES."securimage/images/audio_icon.gif' alt='' align='top' class='tbl-border' style='margin-bottom:1px' /></a><br />\n";
				  echo "<a href='#' onclick=\"document.getElementById('captcha').src = '".INCLUDES."securimage/securimage_show.php?sid=' + Math.random(); return false\"><img src='".INCLUDES."securimage/images/refresh.gif' alt='' align='bottom' class='tbl-border' /></a>\n</td>";
					echo "<td class='tbl2' width='200'><input type='text' name='captcha_code' class='textbox' style='width:195px' /></td>";
					echo "<td class='tbl1' width='50'></td>";
				} else {
					echo "<td class='tbl1' colspan='3' width='450'></td>";
				}
				echo "<td class='tbl2' align='right'><input type='submit' name='save' class='button' style='width:100px' value='".$locale['grrs_49']."' /></td>";
				echo "</tr>\n";
				echo "</table>\n</from>\n";
			}
		} else {
			opentable($locale['grrs_38']);
			echo $locale['grrs_40'];
		}
	} else {
		redirect(FUSION_SELF);
	}
} else {
	opentable($locale['grrs_38']);
	echo $locale['grrs_39'];
}
echo "<div class='small2' align='right'><a href='http://www.granade.eu/scripte/radiostatus.html' target='_blank'>Radiostatus &copy;</a></div>\n";
closetable();
echo "</body>\n</html>\n";
mysql_close();
?>