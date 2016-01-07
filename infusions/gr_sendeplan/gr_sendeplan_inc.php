<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Sendeplan v2.1 for PHP-Fusion 7
| Filename: gr_sendeplan_inc.php
| Author: Ralf Thieme (Gr@n@dE)
| Homepage: www.granade.eu
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

echo "<script type='text/javascript' src='".INFUSIONS."gr_sendeplan/sp_overlib.js'></script><!-- overLIB (c) Erik Bosrup -->\n";
echo "<link rel='stylesheet' href='".INFUSIONS."gr_sendeplan/sp_styles.css' type='text/css' media='Screen' />\n";
function dj_info_box($id="0", $info="Keine Infos", $feld_id, $name="") {
	global $sp_settings, $userdata, $locale;
	$ausgabe = "";
	if (!isnum($id)) fallback("index.php");
	if ($id > 0) {
		$info_result = dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$id."'");
		if (dbrows($info_result) != 0) {
			$user_info = dbarray($info_result);
			if (sp_check($sp_settings['grss_sgroup'], $user_info['user_groups'])) {
				$mod = "<b>".$locale['grsp124']."</b><br />".$locale['grsp125']."<br /><br />";
			} elseif (sp_check($sp_settings['grss_ggroup'], $user_info['user_groups'])) {
				$mod = "<b>".$locale['grsp124']."</b><br />".$locale['grsp126']."<br /><br />";
			} else {
				$mod = "";
			}
			if ($user_info['user_avatar'] != "") { $avatar = IMAGES."avatars/".$user_info['user_avatar']; } else { $avatar = IMAGES."avatars/nopic.gif"; }		
			$infos= "<div align=\'center\'><img src=\'".$avatar."\' /><br /><br /></div><b>".$locale['grsp122']."</b><br /><span class=\'info2\'>".$user_info['user_name']."</span><br /><br />".$mod."<b>".$locale['grsp123']."</b><br />".$info."<br /><br />";
			$ausgabe .= '<a onmouseover="return overlib(\''.$infos.'\', STICKY, FGCLASS, \'sp1\', BGCLASS, \'sp2\', CAPTIONFONTCLASS, \'a\', CLOSEFONTCLASS, \'a\', CAPTION, \''.$locale['grsp139'].'\', RIGHT, CLOSETEXT, \' \');" onmouseout="return nd(\'true\');" href="'.BASEDIR.'profile.php?lookup='.$user_info['user_id'].'">'.($sp_settings['grss_djpic'] == 1 ? "<img src=\"".$avatar."\" height=\"40\" border=\"0\" /><br />" : "").$user_info['user_name'].'</a>';
			if (((sp_group($sp_settings['grss_sgroup']) || sp_group($sp_settings['grss_ggroup'])) && $userdata['user_id'] == $user_info['user_id']) || sp_group($sp_settings['grss_agroup']) || iSUPERADMIN) {
				if ($sp_settings['grss_djedit'] == 1 || sp_group($sp_settings['grss_agroup']) || iSUPERADMIN) {
					$ausgabe .= "<br /><input type='submit' value='".$locale['grsp130']."' class='button' style='width:80px;' onclick='popup=window.open(\"".INFUSIONS."gr_sendeplan/gr_sendeplan_popup.php?status=edit&id=".$feld_id."\",\"DJ_Admin\",\"toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=500,height=220,left=250,top=250\"); return false;' />";
				}
				if ($sp_settings['grss_djoff'] == 1 || sp_group($sp_settings['grss_agroup']) || iSUPERADMIN) {
					$ausgabe .= '<br /><form method="post" action="'.FUSION_SELF.'?id='.$feld_id.'"><input type="submit" name="sp_delete" value="'.$locale['grsp131'].'" class="button" style="width:80px;" /></form>';
				}
			}
		}
	} elseif ($id == 0 && $name != "") {
		$infos= "<div align=\'center\'><img src=\'".IMAGES."avatars/nopic.gif\' /><br /><br /></div><b>".$locale['grsp122']."</b><br /><span class=\'info2\'>".$name."</span><br /><br /><b>".$locale['grsp123']."</b><br />".$info."<br /><br />";
		$ausgabe .= '<a onmouseover="return overlib(\''.$infos.'\', STICKY, FGCLASS, \'sp1\', BGCLASS, \'sp2\', CAPTIONFONTCLASS, \'a\', CLOSEFONTCLASS, \'a\', CAPTION, \''.$locale['grsp139'].'\', RIGHT, CLOSETEXT, \' \');" onmouseout="return nd(\'true\');">'.($sp_settings['grss_djpic'] == 1 ? "<img src=\"".IMAGES."avatars/nopic.gif\" height=\"40\" border=\"0\" /><br />" : "").$name.'</a>';
		if (sp_group($sp_settings['grss_agroup']) || iSUPERADMIN) {
			$ausgabe .= "<br /><input type='submit' value='".$locale['grsp130']."' class='button' style='width:80px;' onclick='popup=window.open(\"".INFUSIONS."gr_sendeplan/gr_sendeplan_popup.php?status=edit&id=".$feld_id."\",\"DJ_Admin\",\"toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=500,height=220,left=250,top=250\"); return false;' />";
			$ausgabe .= '<br /><form method="post" action="'.FUSION_SELF.'?id='.$feld_id.'"><input type="submit" name="sp_delete" value="'.$locale['grsp131'].'" class="button" style="width:80px;" /></form>';
		}
	}
		
	if ($ausgabe == "") {
		if ($sp_settings['grss_djon'] == 1 && !($sp_settings['grss_week'] == 1 && $feld_id < 169) && (sp_group($sp_settings['grss_sgroup']) || sp_group($sp_settings['grss_ggroup'])) || sp_group($sp_settings['grss_agroup']) || iSUPERADMIN) {
			$ausgabe .= "<a href='javascript:;' onclick='popup=window.open(\"".INFUSIONS."gr_sendeplan/gr_sendeplan_popup.php?status=add&id=".$feld_id."\",\"DJ_Admin\",\"toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=1,width=500,height=220,left=250,top=250\"); return false;'>".$locale['grsp121']."</a>";
		} else {
			$ausgabe .= ($sp_settings['grss_djpic'] == 1 && $sp_settings['grss_autodjpic'] == 1 ? "<img src=\"".INFUSIONS."gr_sendeplan/autodj.gif\" height=\"40\" border=\"0\" /><br />" : "").$locale['grsp120'];
		}
	}
	if ($sp_settings['grss_replay'] == 1 && $feld_id > 168 && (sp_group($sp_settings['grss_agroup']) || iSUPERADMIN)) {
		$info_result = dbquery("SELECT * FROM ".DB_GR_SENDEPLAN_REPLAY." WHERE grsr_re_id='".$feld_id."'");
		if (dbrows($info_result) != 0) {
			$ausgabe .= '<form method="post" action="'.FUSION_SELF.'?id='.$feld_id.'"><input type="submit" name="sp_re_delete" value="'.$locale['grsp140'].'" class="button" style="width:80px;" /></form>';
		}
	}
	return $ausgabe;
}

function sp_check($group, $user) {
	if (in_array($group, explode(".", $user))) {
		return true;
	} else {
		return false;
	}
}

function sp_group($group) {
	if (iMEMBER && in_array($group, explode(".", iUSER_GROUPS))) {
		return true;
	} else {
		return false;
	}
}

function sp_update() {
	global $sp_settings;
	$lasttime = $sp_settings['grss_time'] + 3601 * 24;
	if (date("w") == 1 && time() > $lasttime) {
		$i = 169;
		while ($i < 337) {
			$result = dbquery("SELECT * FROM ".DB_GR_SENDEPLAN." WHERE grs_id='".$i."'");
			if (dbrows($result) != 0) {
				$i2 = $i - 168;
				$data = dbarray($result);
				$result = dbquery("UPDATE ".DB_GR_SENDEPLAN." SET grs_user_id='".$data['grs_user_id']."', grs_info='".$data['grs_info']."', grs_name='".$data['grs_name']."' WHERE grs_id='".$i2."'");
				if ($sp_settings['grss_replay'] == 1) {
					$result2 = dbquery("SELECT * FROM ".DB_GR_SENDEPLAN_REPLAY." WHERE grsr_re_id='".$i."'");
					if (dbrows($result2) != 0) {
						$data2 = dbarray($result2);
						$result3 = dbquery("UPDATE ".DB_GR_SENDEPLAN." SET grs_user_id='".$data2['grsr_user_id']."', grs_info='".$data2['grsr_info']."', grs_name='".$data2['grsr_name']."' WHERE grs_id='".$i."'");
					} else {
						$result3 = dbquery("UPDATE ".DB_GR_SENDEPLAN." SET grs_user_id='0', grs_info='', grs_name='' WHERE grs_id='".$i."'");
					}
				} else {
					$result3 = dbquery("UPDATE ".DB_GR_SENDEPLAN." SET grs_user_id='0', grs_info='', grs_name='' WHERE grs_id='".$i."'");
				}
			}
			$i++;
		}
		$zeit = dbquery("UPDATE ".DB_GR_SENDEPLAN_SETTINGS." SET grss_time='".time()."' WHERE grss_id='1'");
	}
}

function sp_offtime($time) {
	global $sp_settings;
	if ($time > 168) { $sp_time=$time-168; } else { $sp_time=$time; }
	if ($sp_settings['grss_offstart'] != $sp_settings['grss_offstop'] && $sp_settings['grss_offstart'] <= $sp_time && $sp_time <= $sp_settings['grss_offstop']) {
		return false;
	} else {
		return true;
	}	
}

if (iMEMBER && IsSeT($_POST['sp_delete']) && isnum($_GET['id'])) {
	$result = dbquery("UPDATE ".DB_GR_SENDEPLAN." SET grs_user_id='0', grs_info='', grs_name='' WHERE grs_id='".$_GET['id']."'");
	redirect(FUSION_SELF);
}

if (iMEMBER && IsSeT($_POST['sp_re_delete']) && isnum($_GET['id'])) {
	if ($_GET['id'] < 169) { $id2 = $_GET['id'] + 168; } else { $id2 = $_GET['id']; }
	$result = dbquery("DELETE FROM ".DB_GR_SENDEPLAN_REPLAY." WHERE grsr_re_id='".$id2."'");
	redirect(FUSION_SELF);
}

?>