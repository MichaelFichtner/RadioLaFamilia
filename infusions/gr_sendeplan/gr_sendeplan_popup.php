<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Title: Gr_Sendeplan v2.1 for PHP-Fusion 7
| Filename: gr_sendeplan_popup.php
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
require_once "../../maincore.php";
require_once THEME."theme.php";

if ($settings['maintenance'] == "1" && !iADMIN) { redirect(BASEDIR."maintenance.php"); }

if (!IsSeT($_GET['id']) || !isnum($_GET['id'])) { die("Access Denied"); }

include INFUSIONS."gr_sendeplan/infusion_db.php";
if (file_exists(INFUSIONS."gr_sendeplan/locale/".LOCALESET."index.php")) {
	include INFUSIONS."gr_sendeplan/locale/".LOCALESET."index.php";
} else {
	include INFUSIONS."gr_sendeplan/locale/German/index.php";
}

$settings_result = dbquery("SELECT * FROM ".DB_GR_SENDEPLAN_SETTINGS."");
$sp_settings = dbarray($settings_result);

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='".$locale['xml_lang']."' lang='".$locale['xml_lang']."'>\n";
echo "<head>\n<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<link rel='stylesheet' href='".THEME."styles.css' type='text/css' media='Screen' />\n";
// if (function_exists("get_head_tags")) { echo get_head_tags(); }
echo "<script type='text/javascript' src='".INCLUDES."jscript.js'></script>\n";
echo "<script type='text/javascript'>
function sp_reload () {
	opener.location.reload();
}
</script>";
echo "</head>\n<body>\n";
include INFUSIONS."gr_sendeplan/gr_sendeplan_inc.php";
opentable($locale['grsp105']);
if (IsSeT($_GET['status']) && $_GET['status'] == "add" && $_GET['status'] != "edit") {
	if (IsSeT($_POST['submit'])) {
		$info = stripinput($_POST['info']);
		if ((iSUPERADMIN || sp_group($sp_settings['grss_agroup'])) && IsSeT($_POST['user_id']) && isnum($_POST['user_id'])) {
			$userid = $_POST['user_id'];
			$name = stripinput($_POST['name']);
			if ($userid == 0 && $name != "") {
				$sp_name = " grs_name='".$name."', ";
				$re_name = $name;
			} else {
				$sp_name = "";
				$re_name = "";
			}
		} elseif (!iSUPERADMIN && !sp_group($sp_settings['grss_agroup']) && $sp_settings['grss_djon'] == 1 && (sp_group($sp_settings['grss_sgroup']) || sp_group($sp_settings['grss_ggroup']))) {
			$userid = $userdata['user_id'];	
			$sp_name = "";
			$re_name = "";
		} else {
			redirect(FUSION_SELF."?id=".$_GET['id']."&status=add&status2=error");
		}
		if ($info != "" && isnum($userid) && isnum($_GET['id'])) {
			$result = dbquery("UPDATE ".DB_GR_SENDEPLAN." SET grs_user_id='".$userid."',".$sp_name." grs_info='".$info."' WHERE grs_id='".$_GET['id']."'");
			if ($_POST['replay'] == 1 && $sp_settings['grss_replay'] == 1) {
				if ($_GET['id'] > 168) {
					$re_id = $_GET['id'];
				} else {
					$re_id = $_GET['id'] + 168;
					$result = dbquery("UPDATE ".DB_GR_SENDEPLAN." SET grs_user_id='".$userid."',".$sp_name." grs_info='".$info."' WHERE grs_id='".$re_id."'");
				}
				$result = dbquery("INSERT INTO ".DB_GR_SENDEPLAN_REPLAY." (grsr_re_id, grsr_user_id, grsr_info, grsr_name) VALUES ('".$re_id."', '".$userid."', '".$info."', '".$re_name."')"); 
			}
			redirect(FUSION_SELF."?id=".$_GET['id']."&status=add&status2=thanks");
		} else {
			redirect(FUSION_SELF."?id=".$_GET['id']."&status=add&status2=error");
		}
	} elseif (IsSeT($_GET['status2']) && $_GET['status2'] == "error") {
		echo "<div align='center'>".$locale['grsp132']."<br /><br /><a href='javascript:;' onclick='window.close();'>".$locale['grsp135']."</a></div>";
	} elseif (IsSeT($_GET['status2']) && $_GET['status2'] == "thanks") {
		echo "<script type='text/javascript'>
		window.onload=sp_reload();
		</script>
		<div align='center'>".$locale['grsp133']."<br /><br /><a href='javascript:;' onclick='window.close();'>".$locale['grsp135']."</a></div>";
	} else {
		echo '<form method="post" action="'.FUSION_SELF.'?status=add&id='.$_GET['id'].'">
			<table width="100%" align="center" class="tbl_border">';
			if (iSUPERADMIN || sp_group($sp_settings['grss_agroup'])) {
				echo '<tr>
					<td class="tbl1" align="right">'.$locale['grsp127'].'<span style="color:#ff0000">*</span></td>
					<td class="tbl2"><select name="user_id" class="textbox" style="width: 200px;">
					<option value="">'.$locale['grsp128'].'</option>\n<option value="0">'.$locale['grsp129'].'</option>\n';
					$result = dbquery("SELECT user_id, user_name, user_groups FROM ".DB_USERS." ORDER BY user_level DESC, user_name");
					while ($member_list = dbarray($result)) {
						if (sp_check($sp_settings['grss_sgroup'], $member_list['user_groups'])) {
							echo "<option".($member_list['user_id'] == $userdata['user_id'] ? " selected" : "")." style='color:red;' value='".$member_list['user_id']."'>".$member_list['user_name']."</option>\n";
						} elseif (sp_check($sp_settings['grss_ggroup'], $member_list['user_groups'])) {
							echo "<option".($member_list['user_id'] == $userdata['user_id'] ? " selected" : "")." style='color:green;' value='".$member_list['user_id']."'>".$member_list['user_name']."</option>\n";
						} else {
							
						}
					}
					echo '</select></td>
				</tr>
				<tr>
					<td class="tbl1" align="right">'.$locale['grsp137'].'<span style="color:#ff0000">*</span></td>
					<td class="tbl2"><input type="text" name="name" class="textbox" size="20" /></td>
				</tr>';
			}
			if ($_GET['id'] < 169) { $replay_id = 168 + $_GET['id']; } else { $replay_id = $_GET['id']; }
			$replay_check = dbquery("SELECT * FROM ".DB_GR_SENDEPLAN_REPLAY." WHERE grsr_re_id='".$replay_id."'");
			if ($sp_settings['grss_replay'] == 1 && dbrows($replay_check) == 0) {
			echo '<tr>
					<td class="tbl1" align="right">'.$locale['grsp154'].'</span></td>
					<td class="tbl2"><input type="checkbox" name="replay" class="textbox" value="1" />'.$locale['grsp138'].'</td>
				</tr>';
			}
			echo '<tr>
					<td class="tbl1" align="right">'.$locale['grsp123'].'<span style="color:#ff0000">*</span></td>
					<td class="tbl2"><input type="text" name="info" class="textbox" size="20" /></td>
				</tr>
				<tr>
					<td class="tbl1" align="center" colspan="2"><input type="submit" name="submit" class="button" value="'.$locale['grsp136'].'" /></td>
				</tr>
				</table>
			</form>';
	}
} elseif (IsSeT($_GET['status']) && $_GET['status'] == "edit" && $_GET['status'] != "add") {
	if (IsSeT($_POST['submit'])) {
		$info = stripinput($_POST['info']);
		if ((iSUPERADMIN || sp_group($sp_settings['grss_agroup']) || $sp_settings['grss_djedit'] == 1 && (sp_group($sp_settings['grss_sgroup']) || sp_group($sp_settings['grss_ggroup']))) && $info != "" && isnum($_GET['id'])) {
			$result = dbquery("UPDATE ".DB_GR_SENDEPLAN." SET grs_info='".$info."' WHERE grs_id='".$_GET['id']."'");
			redirect(FUSION_SELF."?id=".$_GET['id']."&status=edit&status2=thanks");
		} else {
			redirect(FUSION_SELF."?id=".$_GET['id']."&status=edit&status2=error");
		}
	} elseif (IsSeT($_GET['status2']) && $_GET['status2'] == "error") {
		echo "<div align='center'>".$locale['grsp132']."<br /><br /><a href='javascript:;' onclick='window.close();'>".$locale['grsp135']."</a></div>";
	} elseif (IsSeT($_GET['status2']) && $_GET['status2'] == "thanks") {
		echo "<script type='text/javascript'>
		window.onload=sp_reload();
		</script>
		<div align='center'>".$locale['grsp133']."<br /><br /><a href='javascript:;' onclick='window.close();'>".$locale['grsp135']."</a></div>";
	} else {
$result = dbquery("SELECT * FROM ".DB_GR_SENDEPLAN." WHERE grs_id='".$_GET['id']."'");
$data = dbarray($result);
echo '<form method="post" action="'.FUSION_SELF.'?status=edit&id='.$_GET['id'].'">
<table width="100%" align="center" class="tbl-border"><tr>
<td class="tbl1" align="center">'.$locale['grsp123'].'<span style="color:#ff0000">*</span></td>
<td class="tbl2" align="center"><input type="text" name="info" class="textbox" size="20" value="'.$data['grs_info'].'" /></td>
</tr><tr>
<td class="tbl1" align="center" colspan="2"><input type="submit" name="submit" class="button" value="'.$locale['grsp136'].'" /></td>
</tr></table></form>';
	}
} else {
	echo "<div align='center'>".$locale['grsp134']."<br /><br /><a href='javascript:;' onclick='window.close();'>".$locale['grsp135']."</a></div>";
}

echo "<div align='right'><a href='http://www.granade.eu/scripte/sendeplan.html' target='_blank'>Sendeplan &copy;</a></div>";
closetable();

echo "</body>\n</html>\n";
mysql_close();
ob_end_flush();
?>